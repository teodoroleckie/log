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
    /** @var FileHandler */
    private FileHandler $handler;

    /**
     * @test
     */
    public function log(): void
    {
        $root = vfsStream::setup('root');
        $file = $root->url() . "/framework.log";

        $this->handler = new FileHandler(Level::NOTICE, $file);
        $this->handler->log(Level::INFO, "info");
        foreach (['alert', 'critical', 'error', 'warning', 'notice', 'debug'] as $method) {
            call_user_func([$this->handler, $method], $method);
        }

        static::assertEquals('alertcriticalerrorwarningnotice', file_get_contents($file));

        unset($this->handler);
    }
}
