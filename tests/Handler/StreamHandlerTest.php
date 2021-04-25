<?php

namespace Tleckie\Log\Tests\Handler;

use PHPUnit\Framework\TestCase;
use Tleckie\Log\Handler\StreamHandler;
use Tleckie\Log\Level;

/**
 * Class StreamHandlerTest
 *
 * @package Tleckie\Log\Tests\Handler
 * @author  Teodoro Leckie Westberg <teodoroleckie@gmail.com>
 */
class StreamHandlerTest extends TestCase
{
    /** @var StreamHandler */
    private StreamHandler $stream;

    /**
     * @test
     */
    public function log(): void
    {
        $handle = fopen('php://memory', 'a');
        $this->stream = new StreamHandler(Level::INFO, $handle);

        $this->stream->log(Level::INFO, "info");
        foreach (['alert', 'critical', 'error', 'warning', 'notice', 'debug'] as $method) {
            call_user_func([$this->stream, $method], "$method");
        }

        fseek($handle, 0);
        static::assertEquals('infoalertcriticalerrorwarningnotice', fread($handle, 1000));

        static::assertTrue(is_resource($handle));
        $this->stream->__destruct();
        static::assertFalse(is_resource($handle));
    }
}
