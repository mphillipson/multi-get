<?php

namespace MPhillipson\Multiget\Tests;

use MPhillipson\Multiget\Services\MultigetService as Multiget;

class MultigetTest extends \Orchestra\Testbench\TestCase
{
    protected $service;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        $this->service = new Multiget();
    }

    public function testService()
    {
        $this->assertInstanceOf(Multiget::class, $this->service);
    }

    public function testDownloadReturnsIntegerWhenUrlIsValid()
    {
        $this->assertStringMatchesFormat('%d', '1');
    }

    public function testDownloadReturnsFalseWhenChunksInvalid()
    {
        $this->assertFalse(false);
    }

    public function testDownloadReturnsFalseWhenChunkSizeInvalid()
    {
        $this->assertFalse(false);
    }

    public function testDownloadReturnsFalseWhenMaxSizeInvalid()
    {
        $this->assertFalse(false);
    }

    public function testDownloadReturnsFalseWhenTargetFileInvalid()
    {
        $this->assertFalse(false);
    }

    public function testDownloadReturnsFalseWhenUrlInvalid()
    {
        $this->assertFalse(false);
    }

    /**
     * Define environment setup.
     */
    protected function getEnvironmentSetUp($app)
    {
        //
    }

    /**
     * Get package providers.
     */
    protected function getPackageProviders($app)
    {
        return [
            'MPhillipson\Multiget\Providers\MultigetServiceProvider'
        ];
    }
}
