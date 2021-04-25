<?php

namespace Tleckie\Log\Tests\Formatter\Handler;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tleckie\Log\Formatter\Handler\ExceptionHandler;

/**
 * Class ExceptionHandlerTest
 *
 * @package Tleckie\Log\Tests\Handler
 * @author  Teodoro Leckie Westberg <teodoroleckie@gmail.com>
 */
class ExceptionHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function handler(): void
    {
        $handler = new ExceptionHandler();

        $message = new Exception('Lorem Ipsum is simply dummy text', 55);

        $value = str_replace('"Lorem Ipsum is simply dummy text" ', '', $handler->handler($message));
        $array = json_decode($value, true);

        foreach (['class', 'message', 'code', 'file', 'trace'] as $key) {
            static::assertTrue(isset($array[$key]));
        }

        static::assertEquals('/', $array['file'][0]);
    }

    /**
     * @test
     */
    public function handlerNotException(): void
    {
        $handler = new ExceptionHandler();

        $message = 'Lorem Ipsum is simply dummy text';
        $expected = '"Lorem Ipsum is simply dummy text" {}';

        static::assertEquals($expected, $handler->handler($message));
    }

    /**
     * @test
     */
    public function handlerPrevious(): void
    {
        try {
            try {
                throw new Exception('Test message');
            } catch (Exception $exception) {
                throw new InvalidArgumentException('Ops!', 999, $exception);
            }
        } catch (Exception $exception) {
            $handler = new ExceptionHandler();
            static::assertIsString($handler->handler($exception));
        }
    }
}
