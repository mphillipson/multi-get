<?php
/*
 * The following variables are used to assign values to configuration options that
 * are calculated at runtime. If you prefer, you can substitute static values
 * for any of the variables that appear in the configuration options below.
 */

$chunks = 4;
$mebibyte = 1024 * 1024;
$tempDir = sys_get_temp_dir();

return [
    /*
    |--------------------------------------------------------------------------
    | Multi-GET File Download
    |--------------------------------------------------------------------------
    | Here you may configure the default options used for Multi-GET file
    | downloading, including the chunk size (in bytes) and number of chunks to
    | use, the maximum size (in bytes) to download, and the target path for
    | downloaded files, as well as the 3-letter prefix used in naming a
    | downloaded file when the target path does not reference a filename.
    |
    */

    'download' => [
        'chunks' => [
            'number' => $chunks,
            'size' => $mebibyte
        ],
        'max_size' => $chunks * $mebibyte,
        'target_file' => [
            'path' => $tempDir,
            'prefix' => 'mtg'
        ]
    ]
];
