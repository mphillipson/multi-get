<?php

namespace MPhillipson\Multiget\Services;

class MultigetService
{
    public $error;

    protected $chunks;
    protected $chunkSize;
    protected $maxSize;
    protected $targetFile;

    /**
     * Downloads part of a file from a web server, in chunks, and writes the contents to a file.
     *
     * @param  string $url
     * @param  array  $options
     * @return mixed
     */
    public function download($url, array $options = [])
    {
        try {
            $this->setChunks($options['chunks']);
            $this->setChunkSize($options['chunk-size']);
            $this->setMaxSize($options['max-size']);

            $filePart = $this->getPartialFile($url);

            if ($filePart !== '') {
                $this->setTargetFile($options['target-file']);

                return $this->writeTargetFile($filePart);
            }
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }

        return false;
    }

    /**
     * Downloads partial file contents from a URL, using multiple chunked requests executed in parallel.
     *
     * @param  string $url
     * @throws \Exception if error is encountered in cURL connections
     * @return string
     */
    protected function getPartialFile($url)
    {
        $mh = curl_multi_init();

        $channels = array();

        $index = 0;

        $truncated = false;

        // Build array of cURL connection handles to same URL, each with a different byte range,
        // representing a file chunk
        while ($index < $this->chunks && !$truncated) {
            $min = $index * $this->chunkSize;
            $max = $min + $this->chunkSize - 1;

            if ($this->maxSize) {
                if ($truncated = $max >= $this->maxSize - 1) {
                    $max = $this->maxSize - 1;
                }
            }

            $range = $min . '-' . $max;

            $channels[$index] = curl_init();

            curl_setopt_array($channels[$index], [
                CURLOPT_URL => $url,
                CURLOPT_HEADER => false,
                CURLOPT_RANGE => $range,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true
            ]);

            curl_multi_add_handle($mh, $channels[$index]);

            $index++;
        }

        $active = null;

        $error = '';

        // Execute cURL requests asynchronously, exiting loop when all requests are complete
        // or if error is encountered in any connection
        do {
            $status = curl_multi_exec($mh, $active);

            // Test whether result represents error code
            if ($status > 0) {  // Yes, capture error
                $error = curl_multi_strerror($status);
            }
        } while ($active && $status == CURLM_OK);

        $filePart = '';

        // Assemble partial file by concatenating downloaded file chunks, in order
        foreach ($channels as $index) {
            if ($error === '') {
                $filePart .= curl_multi_getcontent($index);
            }

            curl_multi_remove_handle($mh, $index);
            curl_close($index);
        }

        curl_multi_close($mh);

        if ($error !== '') {
            throw new \Exception($error);
        }

        return $filePart;
    }

    /**
     * Sets the number of chunks to divide the download process into.
     *
     * @param  int $chunks
     * @throws \Exception if
     * @return void
     */
    protected function setChunks($chunks)
    {
        // Test whether default chunk number is being overridden
        if ((string) $chunks === '') {  // No, use config default
            $chunks = config('multiget.download.chunks.number');
        }

        $chunks = (int) $chunks;

        if (!$chunks) {
            throw new \Exception('Invalid or undefined number of chunks.');
        }

        $this->chunks = $chunks;
    }

    /**
     * Sets the size (in bytes) of each chunk to be downloaded.
     *
     * @param  int $chunkSize
     * @throws \Exception if
     * @return void
     */
    protected function setChunkSize($chunkSize)
    {
        // Test whether default chunk size is being overridden
        if ((string) $chunkSize === '') {  // No, use config default
            $chunkSize = config('multiget.download.chunks.size');
        }

        $chunkSize = (int) $chunkSize;

        if (!$chunkSize) {
            throw new \Exception('Invalid or undefined chunk size.');
        }

        $this->chunkSize = $chunkSize;
    }

    /**
     * Sets the total size (in bytes) to be downloaded.
     *
     * @param  int $maxSize
     * @throws \Exception if
     * @return void
     */
    protected function setMaxSize($maxSize)
    {
        // Test whether default max download size is being overridden
        if ((string) $maxSize === '') {  // No, use config default
            $maxSize = config('multiget.download.max_size');
        }

        $maxSize = (int) $maxSize;

        if (!$maxSize) {
            throw new \Exception('Invalid or undefined maximum size.');
        }

        $this->maxSize = $maxSize;
    }

    /**
     * Sets the target path where the partially downloaded file will be written.
     *
     * @param  string $targetFile
     * @throws \Exception if target file is not defined and unique filename cannot be created in default target path
     * @return void
     */
    protected function setTargetFile($targetFile)
    {
        // Test whether target file path is specified
        if ((string) $targetFile === '') {  // No, use config default
            $targetFile = tempnam(
                config('multiget.download.target_file.path'),
                config('multiget.download.target_file.prefix')
            );

            if ($targetFile === false) {
                throw new \Exception('Could not create output file in default target file path.');
            }
        }

        $this->targetFile = $targetFile;
    }

    /**
     * Writes the partially downloaded file to the target location.
     *
     * @param  string $filePart
     * @throws \Exception if partial file contents cannot be written to target location
     * @return int
     */
    protected function writeTargetFile($filePart)
    {
        $fp = fopen($this->targetFile, 'wb');

        if ($fp === false) {
            throw new \Exception('Could not open target output file for writing.');
        }

        $bytes = fwrite($fp, $filePart);

        fclose($fp);

        return $bytes;
    }
}