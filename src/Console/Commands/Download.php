<?php

namespace Mphillipson\Multiget\Console\Commands;

use Illuminate\Console\Command;
use Mphillipson\Multiget\Services\MultigetService as Multiget;

class Download extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'multiget:download
                            {url : The URL of the source file}
                            {--t|target-file=*" : The local file destination path }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Downloads part of a file from a web server, in chunks';

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
        $this->multiget->download($this->argument('url'), $this->option('target-file'));
    }
}