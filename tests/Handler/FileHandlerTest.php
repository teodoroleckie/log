<?php

namespace Tleckie\Log\Tests\Handler;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Tleckie\Log\Handler\FileHandler;
use Tleckie\Log\Level;

/**
 * Class FileHandlerTest
 *
 * @package Tleckie\Log\Tests\Handler
 * @author  Teodoro Leckie Westberg <teodoroleckie@gmail.com>
 */
class FileHandlerTest extends TestCase
{

    /**
     * @test
     */
    public function log(): void
    {
        vfsStream::setup();
        vfsStream::create([ ['framework.log']]);

        $handler = new FileHandler(Level::NOTICE, vfsStream::url('root').'/framework.log');
        $handler->log(Level::INFO, "info");
        foreach (['alert', 'critical', 'error', 'warning', 'notice', 'debug'] as $method) {
            call_user_func([$handler, $method], $method);
        }

        static::assertEquals('alertcriticalerrorwarningnotice', file_get_contents(vfsStream::url('root').'/framework.log'));

        unset($handler);
    }
}
