<?php

namespace Tleckie\Log\Tests;

use JsonException;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Tleckie\Log\Formatter\Handler\ExceptionHandler;
use Tleckie\Log\Level;
use Tleckie\Log\Log;
use DateTimeImmutable;
use Psr\Log\InvalidArgumentException;

/**
 * Class LogTest
 *
 * @package Tleckie\Log\Tests
 * @author  Teodoro Leckie Westberg <teodoroleckie@gmail.com>
 */
class LogTest extends TestCase
{
    /** @var Log */
    private Log $logger;

    /** @var LoggerInterface|MockObject */
    private LoggerInterface|MockObject $handlerMock;

    public function setUp(): void
    {
        $this->handlerMock = $this->createMock(LoggerInterface::class);

        $handlers = [$this->handlerMock];

        $this->logger = new Log($handlers);
    }

    /**
     * @test
     */
    public function log(): void
    {
        $format = sprintf(
            '[%s] channel.INFO "message info" {} {"TYPE":"info"}',
            (new DateTimeImmutable('now'))->format('Y-m-d\TH:i:sP'),
        )."\n";

        $this->handlerMock
            ->expects(static::once())
            ->method('log')
            ->with(Level::INFO, $format, ['TYPE' => Level::INFO]);

        $this->logger->log(Level::INFO, 'message {TYPE}', ['TYPE' => Level::INFO]);
    }

    /**
     * @test
     */
    public function logInvalidLevel(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->logger->log('INVALID-LEVEL', 'message {TYPE}', ['TYPE' => Level::INFO]);
    }

    /**
     * @test
     */
    public function invalidHandler(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Handlers have to be of type Psr\Log\LoggerInterface');

        $handlers = [new class() {}];
        $this->logger = new Log($handlers);
    }

    /**
     * @test
     */
    public function logMethods(): void
    {
        $this->handlerMock
            ->expects(static::exactly(7))
            ->method('log');

        $this->logger->log(Level::INFO, 'message {TYPE}', ['TYPE' => Level::INFO]);

        foreach (['alert','critical', 'error','warning','notice','debug'] as $method) {
            call_user_func([$this->logger, $method], "$method");
        }
    }
}
