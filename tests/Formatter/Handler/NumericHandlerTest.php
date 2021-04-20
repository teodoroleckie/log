<?php

namespace Tleckie\Log\Tests\Handler;

use PHPUnit\Framework\TestCase;
use Tleckie\Log\Formatter\Handler\NumericHandler;

/**
 * Class NumericHandlerTest
 *
 * @package Tleckie\Log\Tests\Handler
 * @author  Teodoro Leckie Westberg <teodoroleckie@gmail.com>
 */
class NumericHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function handler(): void
    {
        $handler = new NumericHandler();

        $message = 8888;
        $expected = '"8888" {}';

        static::assertEquals($expected, $handler->handler($message));
    }

    /**
     * @test
     */
    public function handlerNotNumeric(): void
    {
        $handler = new NumericHandler();

        $message = 'Lorem Ipsum is simply {TYPE} text of the printing and typesetting industry';
        $expected = '"Lorem Ipsum is simply {TYPE} text of the printing and typesetting industry" {}';

        static::assertEquals($expected, $handler->handler($message));
    }
}
