<?php

namespace Tleckie\Log\Formatter\Handler;

use JsonSerializable;

/**
 * Class ObjectHandler
 *
 * @package Tleckie\Log\Formatter\Handler
 * @author  Teodoro Leckie Westberg <teodoroleckie@gmail.com>
 */
class ObjectHandler extends Handler
{
    /**
     * @param mixed $message
     * @param array $context
     * @return string
     */
    public function handler(mixed $message, array $context = []): string
    {
        if (is_object($message)) {
            if ($message instanceof JsonSerializable) {
                return parent::handler($this->encode($message));
            }

            if (method_exists($message, '__toString')) {
                return parent::handler($message);
            }

            return $this->encode(get_class($message)) . ' {}';
        }

        return parent::handler($message, $context);
    }
}
