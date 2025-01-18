<?php declare(strict_types=1);

namespace Craftix\Requester\Logger;

use Swoole\Atomic;

class Metrics
{
    private Atomic $successCount;
    private Atomic $failureCount;
    private float $startTime;
    private float $endTime;

    public function __construct()
    {
        $this->successCount = new Atomic(0);
        $this->failureCount = new Atomic(0);
    }

    public function startTimer(): void
    {
        $this->startTime = microtime(true);
    }

    public function endTimer(): void
    {
        $this->endTime = microtime(true);
    }

    public function incrementSuccess(): void
    {
        $this->successCount->add();
    }

    public function incrementFailure(): void
    {
        $this->failureCount->add();
    }

    public function getDuration(): float
    {
        return $this->endTime - $this->startTime;
    }

    public function getSuccessCount(): int
    {
        return $this->successCount->get();
    }

    public function getFailureCount(): int
    {
        return $this->failureCount->get();
    }
}