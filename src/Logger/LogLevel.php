<?php declare(strict_types=1);

namespace Craftix\Requester\Logger;

enum LogLevel: int
{
    case INFO = 2;
    case WARN = 4;
    case ERR = 8;
    case SUCCESS = 16;
    case DEBUG = 32;

    /** Enable all logs levels in debug mode  */
    case ALL = self::SUCCESS->value|self::ERR->value|self::WARN->value|self::INFO->value|self::DEBUG->value;

    /** Enable just important levels in production */
    case PRODUCTION = self::SUCCESS->value|self::ERR->value|self::WARN->value;
}