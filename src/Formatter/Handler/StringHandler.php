<?php

namespace Tleckie\Log\Formatter\Handler;

/**
 * Class StringHandler
 *
 * @package Tleckie\Log\Formatter\Handler
 * @author  Teodoro Leckie Westberg <teodoroleckie@gmail.com>
 */
class StringHandler extends Handler
{
    /**
     * @param mixed $message
     * @param array $context
     * @return string
     */
    public function handler(mixed $message, array $context = []): string
    {
        if (is_string($message)) {
            $replace = [];
            foreach ($context as $key => $value) {
                if (is_string($value) || is_numeric($value) || method_exists($value, '__toString')) {
                    $replace['{' . $key . '}'] = $value;
                }
            }
            $message = sprintf('%s', strtr($message, $replace));
        }

        return parent::handler($message, $context);
    }
}
