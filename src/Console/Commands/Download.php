<?php

namespace MPhillipson\Multiget\Console\Commands;

use Illuminate\Console\Command;
use MPhillipson\Multiget\Services\MultigetService as Multiget;

/**
 * Artisan command for Multi-GET download of a partial file.
 *
 * @author Mike Phillipson <michael@phillipsontx.com>
 */
class Download extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'multiget:download
                            {url : The URL of the source file.}
                            {--c|chunks= : The number of file chunks to download (optional).}
                            {--m|max-size= : The maximum file download size in bytes (optional).}
                            {--s|chunk-size= : The size in bytes of each file chunk (optional).}
                            {--t|target-file= : The full path to the destination file (optional).}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Downloads part of a file from a web server, in chunks.';

    /**
     * The Multi-GET service.
     *
     * @var Multiget
     */
    protected $multiget;

    /**
     * Create a new command instance.
     *
     * @param  Multiget $multiget
     * @return void
     */
    public function __construct(Multiget $multiget)
    {
        parent::__construct();

        $this->multiget = $multiget;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $bytes = $this->multiget->download($this->argument('url'), $this->options());

        if ($bytes !== false) {
            $this->info($bytes . ' bytes written successfully.');
        } else {
            $this->error($this->multiget->error ?: 'An unknown error was encountered.');
        }
    }
}