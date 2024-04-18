<?php

namespace ITCAN\LaravelHelpers\Tests;

use ITCAN\LaravelHelpers\GlobalHelpersServiceProvider;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase as BaseTestCase;
use ReflectionClass;

Mockery::globalHelpers();

abstract class TestCase extends BaseTestCase
{
    use MockeryPHPUnitIntegration;

    public static $functions;

    public function setUp(): void
    {
        $this->createDummyprovider()->register();

        self::$functions = mock();
    }

    protected function createDummyprovider(): GlobalHelpersServiceProvider
    {
        $reflectionClass = new ReflectionClass(GlobalHelpersServiceProvider::class);

        return $reflectionClass->newInstanceWithoutConstructor();
    }
}
