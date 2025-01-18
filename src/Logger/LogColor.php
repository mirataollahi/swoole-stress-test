<?php declare(strict_types=1);

namespace Craftix\Requester\Logger;

enum LogColor: int
{
    case RESET = 0;
    case BLACK = 30;
    case RED = 31;
    case GREEN = 32;
    case YELLOW = 33;
    case BLUE = 34;
    case MAGENTA = 35;
    case CYAN = 36;
    case WHITE = 37;
}