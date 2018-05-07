<?php

namespace MPhillipson\Multiget\Tests;

use MPhillipson\Multiget\Services\MultigetService as Multiget;

class MultigetTest extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return ['MPhillipson\Providers\MultigetServiceProvider'];
    }
}
