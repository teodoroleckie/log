<?php

namespace Tleckie\Log\Formatter\Handler;

use Exception;
use Throwable;

/**
 * Class ExceptionHandler
 *
 * @package Tleckie\Log\Formatter\Handler
 * @author  Teodoro Leckie Westberg <teodoroleckie@gmail.com>
 */
class ExceptionHandler extends Handler
{
    /**
     * @param mixed $message
     * @param array $context
     * @return string
     */
    public function handler(mixed $message, array $context = []): string
    {
        if ($message instanceof Exception) {
            $msg = $this->encode($this->clearEof($message->getMessage()));
            $exception = $this->encode($this->format($message));

            return sprintf('%s %s', $msg, $exception);
        }

        return parent::handler($message, $context);
    }

    /**
     * @param Throwable $exception
     * @return array
     */
    private function format(Throwable $exception): array
    {
        $data = [
            'class' => get_class($exception),
            'message' => $this->clearEof($exception->getMessage()),
            'code' => $exception->getCode(),
            'file' => sprintf("%s:%s", $exception->getFile(), $exception->getLine())
        ];

        $trace = $exception->getTrace();
        foreach ($trace as $frame) {
            if (isset($frame['file'])) {
                $data['trace'][] = sprintf("%s:%s", $frame['file'], $frame['line']);
            }
        }

        if ($previous = $exception->getPrevious()) {
            $data['previous'] = $this->format($previous);
        }

        return $data;
    }
}
