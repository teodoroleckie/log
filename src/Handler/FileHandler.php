<?php

namespace Tleckie\Log\Handler;

use Tleckie\Log\Level;

/**
 * Class FileHandler
 *
 * @package Tleckie\Log
 * @author  Teodoro Leckie Westberg <teodoroleckie@gmail.com>
 */
class FileHandler extends AbstractHandler
{
    /** @var resource */
    protected $file;

    /**
     * FileHandler constructor.
     *
     * @param string $minimumLevel
     * @param string $file
     */
    public function __construct(string $minimumLevel = Level::DEBUG, string $file = '/tmp/error.log')
    {
        parent::__construct($minimumLevel);

        $this->file = $file;
    }

    /**
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     */
    public function log($level, $message, array $context = array()): void
    {
        if ($this->canNotify($level)) {
            @file_put_contents($this->file, $message, FILE_APPEND);
        }
    }
}
