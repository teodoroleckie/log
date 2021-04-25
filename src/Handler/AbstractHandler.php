<?php

namespace Tleckie\Log\Handler;

use Psr\Log\AbstractLogger;
use Tleckie\Log\Level;

/**
 * Class AbstractHandler
 *
 * @package Tleckie\Log\Handler
 * @author  Teodoro Leckie Westberg <teodoroleckie@gmail.com>
 */
abstract class AbstractHandler extends AbstractLogger
{
    /** @var array */
    protected const LEVELS = [
        Level::EMERGENCY => 0,
        Level::ALERT => 1,
        Level::CRITICAL => 2,
        Level::ERROR => 3,
        Level::WARNING => 4,
        Level::NOTICE => 5,
        Level::INFO => 6,
        Level::DEBUG => 7
    ];

    /** @var string */
    protected string $minimumLevel;

    /**
     * AbstractHandler constructor.
     *
     * @param string $minimumLevel
     */
    public function __construct(string $minimumLevel = Level::DEBUG)
    {
        $this->minimumLevel = $minimumLevel;
    }

    /**
     * @param string $level
     * @return bool
     */
    protected function canNotify(string $level): bool
    {
        return static::LEVELS[$this->minimumLevel] >= static::LEVELS[$level];
    }
}
