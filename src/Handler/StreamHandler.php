<?php

namespace Tleckie\Log\Handler;

use Tleckie\Log\Level;

/**
 * Class StreamHandler
 *
 * @package Tleckie\Log
 * @author  Teodoro Leckie Westberg <teodoroleckie@gmail.com>
 */
class StreamHandler extends AbstractHandler
{
    /** @var resource */
    protected $stream;

    /**
     * StreamHandler constructor.
     *
     * @param string $minimumLevel
     * @param string $stream
     * @param string $mode
     */
    public function __construct(
        string $minimumLevel = Level::DEBUG,
        $stream = 'php://stdout',
        $mode = 'a'
    ) {
        parent::__construct($minimumLevel);

        $this->stream = (is_resource($stream)) ? $stream : @fopen($stream, $mode);
    }

    /**
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     */
    public function log($level, $message, array $context = array()): void
    {
        if ($this->canNotify($level)) {
            fwrite($this->stream, $message);
        }
    }

    public function __destruct()
    {
        if (is_resource($this->stream)) {
            fclose($this->stream);
        }
    }
}
