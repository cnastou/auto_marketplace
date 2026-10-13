<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Cache;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Replace real Redis with an in-memory cache during tests.
        config()->set('cache.stores.redis', [
            'driver' => 'array',
        ]);

        Cache::forgetDriver('redis');
    }
}
