<?php

namespace Tleckie\Log;

use DateTimeImmutable;
use Psr\Log\AbstractLogger;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Tleckie\Log\Formatter\Handler\ArrayHandler;
use Tleckie\Log\Formatter\Handler\ExceptionHandler;
use Tleckie\Log\Formatter\Handler\HandlerInterface;
use Tleckie\Log\Formatter\Handler\NumericHandler;
use Tleckie\Log\Formatter\Handler\ObjectHandler;
use Tleckie\Log\Formatter\Handler\StringHandler;

/**
 * Class Log
 * Default format: [%date%] %channel%.%level% %message% %context%
 *
 * @package Tleckie\Log
 * @author  Teodoro Leckie Westberg <teodoroleckie@gmail.com>
 */
class Log extends AbstractLogger
{
    /** @var array */
    protected const LEVELS = [
        Level::EMERGENCY,
        Level::ALERT,
        Level::CRITICAL,
        Level::ERROR,
        Level::WARNING,
        Level::NOTICE,
        Level::INFO,
        Level::DEBUG
    ];

    /** @var HandlerInterface */
    protected HandlerInterface $handlerFormatter;

    /** @var LoggerInterface[] */
    private array $handlers;

    /** @var string */
    private string $channel;

    /** @var string */
    private string $format;

    /**
     * Log constructor.
     *
     * @param LoggerInterface[] $handlers
     * @param string            $channel
     * InvalidArgumentException
     * @param string            $format
     */
    public function __construct(
        array $handlers,
        string $channel = 'channel',
        string $format = '[%date%] %channel%.%level% %message% %context%'
    ) {
        foreach ($handlers as $handler) {
            if (!$handler instanceof LoggerInterface) {
                throw new InvalidArgumentException("Handlers have to be of type " . LoggerInterface::class);
            }
        }

        $this->channel = $channel;
        $this->handlers = $handlers;
        $this->format = $format;
        $this->handlerFormatter();
    }

    private function handlerFormatter(): void
    {
        $this->handlerFormatter = new NumericHandler();
        $this->handlerFormatter
            ->next(new ArrayHandler())
            ->next(new ExceptionHandler())
            ->next(new ObjectHandler())
            ->next(new StringHandler());
    }

    /**
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     * @throws InvalidArgumentException
     */
    public function log($level, $message, array $context = array()): void
    {
        if (!in_array($level, static::LEVELS, true)) {
            throw new InvalidArgumentException("Unrecognized [$level] level");
        }

        $stringMessage = $this->handlerFormatter->handler($message, $context);
        $formattedMessage = $this->format($level, $stringMessage, $context);

        foreach ($this->handlers as $handler) {
            $handler->log($level, $formattedMessage, $context);
        }
    }

    /**
     * @param string $level
     * @param string $message
     * @param array  $context
     * @return string
     */
    private function format(string $level, string $message, array $context): string
    {
        $replace = [
            '%date%' => (new DateTimeImmutable('now'))->format('Y-m-d\TH:i:sP'),
            '%channel%' => $this->channel,
            '%level%' => strtoupper($level),
            '%message%' => $message,
            '%context%' => json_encode($context, JSON_UNESCAPED_SLASHES) ?? ''
        ];

        $format = $this->format;
        foreach ($replace as $search => $item) {
            $format = str_replace($search, $item, $format);
        }

        return $format . "\n";
    }
}
