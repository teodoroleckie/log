<?php

namespace Tleckie\Log\Tests\Formatter\Handler;

use Exception;
use PHPUnit\Framework\TestCase;
use Tleckie\Log\Formatter\Handler\ExceptionHandler;
use Tleckie\Log\Formatter\Handler\Handler;

/**
 * Class HandlerTest
 *
 * @package Tleckie\Log\Tests\Handler
 * @author  Teodoro Leckie Westberg <teodoroleckie@gmail.com>
 */
class HandlerTest extends TestCase
{
    /**
     * @test
     */
    public function next(): void
    {
        $handler = new Handler();
        $handler->next(new ExceptionHandler());

        $message = new Exception("Lorem Ipsum is simply \ndummy text", 55);

        $value = str_replace('"Lorem Ipsum is simply dummy text" ', '', $handler->handler($message));
        $array = json_decode($value, true);

        static::assertEquals('Lorem Ipsum is simply dummy text', $array['message']);
        foreach (['class', 'message', 'code', 'file', 'trace'] as $key) {
            static::assertTrue(isset($array[$key]));
        }
    }
}
