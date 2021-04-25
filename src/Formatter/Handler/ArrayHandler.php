<?php

namespace Tleckie\Log\Formatter\Handler;

/**
 * Class ArrayHandler
 *
 * @package Tleckie\Log\Formatter\Handler
 * @author  Teodoro Leckie Westberg <teodoroleckie@gmail.com>
 */
class ArrayHandler extends Handler
{
    /**
     * @param mixed $message
     * @param array $context
     * @return string
     */
    public function handler(mixed $message, array $context = []): string
    {
        if (is_array($message)) {
            $message = $this->encode($message);
        }

        return parent::handler($message, $context);
    }
}
