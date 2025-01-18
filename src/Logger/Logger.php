<?php declare(strict_types=1);

namespace Craftix\Requester\Logger;

use Swoole\Coroutine;

class Logger
{
    public ?string $prefix = null;
    public static int $logLevel = LogLevel::ALL->value;
    public bool $concurrentPrintInCli = false;
    public static self $instance;
    public static array $hiddenTags = [
        'REMOTE_SERV', 'DATABASE'
    ];

    public function __construct(?string $logPrefix = null)
    {
        $this->prefix = $logPrefix;
    }

    /**
     * Colorize the output string in command line
     */
    private function makeOutputText(string $message, LogLevel $level): string
    {
        $dateTime = date('Y-m-d H:i:s');
        $sectionPrefix = $this->prefix ? " [$this->prefix] " : null;
        $levelText = $this->getLevelText($level);
        return "[$levelText] [$dateTime] $sectionPrefix ➡ $message";
    }

    private function addColor(string &$message,LogColor $color): void
    {
        $message = "\033[{$color->value}m$message\033[0m";
    }

    public function info(string $message): void
    {
        if ($this->isLevelEnable(LogLevel::INFO)) {
            $output = $this->makeOutputText($message, LogLevel::INFO);
            $this->addColor($output, LogColor::BLUE);
            $this->print($output);
        }
    }

    public function success(string $message): void
    {
        if ($this->isLevelEnable(LogLevel::SUCCESS)) {
            $output = $this->makeOutputText($message, LogLevel::SUCCESS);
            $this->addColor($output, LogColor::GREEN);
            $this->print($output);
        }
    }

    public function warning(string $message): void
    {
        if ($this->isLevelEnable(LogLevel::WARN)) {
            $output = $this->makeOutputText($message,LogLevel::WARN);
            $this->addColor($output, LogColor::YELLOW);
            $this->print($output);
        }
    }

    public function error(string $message): void
    {
        if ($this->isLevelEnable(LogLevel::ERR)) {
            $output = $this->makeOutputText($message,LogLevel::ERR);
            $this->addColor($output, LogColor::RED);
            $this->print($output);
        }
    }

    public function debug(string $message): void
    {
        if ($this->isLevelEnable(LogLevel::DEBUG)) {
            $output = $this->makeOutputText($message,LogLevel::DEBUG);
            $this->addColor($output, LogColor::CYAN);
            $this->print($output);
        }
    }

    public function getLevelText(LogLevel $level): string
    {
        return match ($level) {
            LogLevel::INFO => 'INFO  ',
            LogLevel::SUCCESS => 'SUCCESS',
            LogLevel::ERR => ' ERROR ',
            LogLevel::WARN => ' WARN  ',
            LogLevel::DEBUG => ' DEBUG ',
            default => 'UNKNOWN',
        };
    }

    public function isLevelEnable(LogLevel $logLevel): bool
    {
        $isLevelActive = self::$logLevel & $logLevel->value;
        $isTagHidden = in_array($this->prefix, self::$hiddenTags);
        return $isLevelActive && !$isTagHidden;
    }

    public function print(string|null $message = null): void
    {
        if ($this->concurrentPrintInCli)
            Coroutine::create(function () use ($message) {
                echo $message.PHP_EOL;
            });
        echo $message.PHP_EOL;
    }
    public static function getInstance(): self
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function echo(string $message,LogLevel $level = LogLevel::INFO): void
    {
        $logger = self::getInstance();
        $logger->info($message);
        match ($level) {
            LogLevel::ERR => $logger->error($message),
            LogLevel::WARN => $logger->warning($message),
            LogLevel::SUCCESS => $logger->success($message),
            LogLevel::DEBUG => $logger->debug($message),
            default => $logger->info($message),
        };
    }

}