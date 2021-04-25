<?php

namespace Tleckie\Log\Tests\Formatter\Handler;

use JsonSerializable;
use PHPUnit\Framework\TestCase;
use Tleckie\Log\Formatter\Handler\ObjectHandler;

/**
 * Class ObjectHandlerTest
 *
 * @package Tleckie\Log\Tests\Handler
 * @author  Teodoro Leckie Westberg <teodoroleckie@gmail.com>
 */
class ObjectHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function handler(): void
    {
        $handler = new ObjectHandler();
        $message = new class() implements JsonSerializable {
            public function jsonSerialize(): array
            {
                return ['name' => 'Jhon'];
            }
        };
        $expected = json_encode('{"name":"Jhon"}') . ' {}';
        static::assertEquals($expected, $handler->handler($message));
    }

    /**
     * @test
     */
    public function handlerToString(): void
    {
        $handler = new ObjectHandler();
        $expected = '"My string" {}';
        static::assertEquals($expected, $handler->handler(new DummyToString()));
    }

    /**
     * @test
     */
    public function handlerObject(): void
    {
        $handler = new ObjectHandler();
        $message = new Dummy();
        $expected = sprintf('%s {}', json_encode(get_class($message), JSON_UNESCAPED_SLASHES));

        static::assertEquals($expected, $handler->handler($message));
    }

    /**
     * @test
     */
    public function notObject(): void
    {
        $handler = new ObjectHandler();
        $message = 'Lorem Ipsum is simply {TYPE} text of the printing and typesetting industry';
        $expected = '"Lorem Ipsum is simply {TYPE} text of the printing and typesetting industry" {}';

        static::assertEquals($expected, $handler->handler($message));
    }
}

class Dummy
{
}

class DummyToString
{
    public function __toString(): string
    {
        return 'My string';
    }
}
