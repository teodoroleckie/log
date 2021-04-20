<?php

namespace Tleckie\Log\Formatter\Handler;

/**
 * Interface HandlerInterface
 *
 * @package Tleckie\Log\Formatter\Handler
 * @author  Teodoro Leckie Westberg <teodoroleckie@gmail.com>
 */
interface HandlerInterface
{
    /**
     * @param HandlerInterface $handler
     * @return HandlerInterface
     */
    public function next(HandlerInterface $handler): HandlerInterface;

    /**
     * @param mixed $message
     * @param array $context
     * @return string
     */
    public function handler(mixed $message, array $context = []): string;
}
