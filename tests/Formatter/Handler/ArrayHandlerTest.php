<?php

namespace Tleckie\Log\Tests\Handler;

use PHPUnit\Framework\TestCase;
use Tleckie\Log\Formatter\Handler\ArrayHandler;

/**
 * Class ArrayHandlerTest
 *
 * @package Tleckie\Log\Tests\Handler
 * @author  Teodoro Leckie Westberg <teodoroleckie@gmail.com>
 */
class ArrayHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function handler(): void
    {
        $handler = new ArrayHandler();

        $message = ['name' => 'Jhon'];
        $expected = json_encode(json_encode($message), JSON_UNESCAPED_SLASHES). ' {}';
        static::assertEquals($expected, $handler->handler($message));
    }

    /**
     * @test
     */
    public function handlerNotArray(): void
    {
        $handler = new ArrayHandler();

        $message = 'Lorem Ipsum is simply {TYPE} text of the printing and typesetting industry';
        $expected = '"Lorem Ipsum is simply {TYPE} text of the printing and typesetting industry" {}';

        static::assertEquals($expected, $handler->handler($message));
    }
}
