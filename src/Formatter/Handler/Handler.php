<?php

namespace Tleckie\Log\Formatter\Handler;

use PHP_EOL;

/**
 * Class Handler
 *
 * @package Tleckie\Log\Formatter\Handler
 * @author  Teodoro Leckie Westberg <teodoroleckie@gmail.com>
 */
class Handler implements HandlerInterface
{
    /** @var HandlerInterface|null */
    protected HandlerInterface|null $nextHandler = null;

    /**
     * @param HandlerInterface $handler
     * @return HandlerInterface
     */
    public function next(HandlerInterface $handler): HandlerInterface
    {
        $this->nextHandler = $handler;

        return $handler;
    }

    /**
     * @param mixed $message
     * @param array $context
     * @return string
     */
    public function handler(mixed $message, array $context = []): string
    {
        if ($this->nextHandler instanceof HandlerInterface) {
            return $this->nextHandler->handler($message, $context);
        }

        $clear = addslashes($this->clearEof($message));

        return sprintf('"%s" {}', $clear);
    }

    /**
     * @param string $value
     * @return string
     */
    protected function clearEof(string $value): string
    {
        return str_replace(PHP_EOL, '', $value);
    }

    /**
     * @param $value
     * @return string
     */
    protected function encode($value): string
    {
        return json_encode($value, JSON_UNESCAPED_SLASHES) ?? '';
    }
}
