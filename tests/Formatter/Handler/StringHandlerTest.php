<?php

namespace Tleckie\Log\Tests\Handler;

use PHPUnit\Framework\TestCase;
use Tleckie\Log\Formatter\Handler\StringHandler;
use Tleckie\Log\Handler\StreamHandler;
use Tleckie\Log\Level;

/**
 * Class StringHandlerTest
 *
 * @package Tleckie\Log\Tests\Handler
 * @author  Teodoro Leckie Westberg <teodoroleckie@gmail.com>
 */
class StringHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function handler(): void
    {
        $handler = new StringHandler();

        $message = 'Lorem Ipsum is simply {TYPE} text of the printing and typesetting industry';
        $context = [
            'TYPE' => 'dummy'
        ];

        $expected = '"Lorem Ipsum is simply dummy text of the printing and typesetting industry" {}';

        static::assertEquals($expected, $handler->handler($message, $context));
    }

    /**
     * @test
     */
    public function handlerNotString(): void
    {
        $handler = new StringHandler();

        $message = 8888;

        $expected = '"8888" {}';

        static::assertEquals($expected, $handler->handler($message));
    }

    /**
     * @test
     */
    public function handlerContext(): void
    {
        $handler = new StringHandler();

        $message = 'Lorem Ipsum is simply {TYPE} text of the printing and typesetting industry';
        $context = [
            'TYPE' => new class() {
                public function __toString(): string
                {
                    return 'dummy';
                }
            }
        ];

        $expected = '"Lorem Ipsum is simply dummy text of the printing and typesetting industry" {}';

        static::assertEquals($expected, $handler->handler($message, $context));
    }
}
