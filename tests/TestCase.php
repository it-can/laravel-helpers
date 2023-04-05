<?php

namespace ITCAN\LaravelHelpers\Tests;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

Mockery::globalHelpers();

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    use MockeryPHPUnitIntegration;

    public static $functions;

    public function setUp(): void
    {
        self::$functions = mock();
    }
}
