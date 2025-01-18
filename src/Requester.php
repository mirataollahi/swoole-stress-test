<?php declare(strict_types=1);

namespace Craftix\Requester;

use Craftix\Requester\Client\HttpClient;
use Craftix\Requester\Logger\Logger;
use Craftix\Requester\Logger\Metrics;
use Swoole\Coroutine;
use Swoole\Coroutine\Channel;
use Throwable;

class Requester
{
    /** @var Logger Logger instance for logging */
    private Logger $logger;

    /** @var Config Configuration for the stress test */
    private Config $config;

    /** @var Metrics Metrics tracker for the stress test */
    private Metrics $metrics;

    /** @var Channel Channel for controlling concurrent requests */
    private Channel $queue;

    /**
     * Requester constructor.
     *
     * @param Config $config The configuration object containing the stress test settings.
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->logger = new Logger();
        $this->metrics = new Metrics();
        $this->queue = new Channel($this->config->getConcurrency());

        try {
            $this->logger->info("Starting stress test...");
            Coroutine::sleep(1);
            $this->startTest();
        } catch (Throwable $exception) {
            $this->logger->error("Startup error: {$exception->getMessage()}");
        }
    }

    /**
     * Run the stress test with the given configuration.
     *
     * @param Config $config The configuration object for the stress test.
     */
    public static function run(Config $config): void
    {
        Coroutine\run(function () use ($config) {
            new self($config);
        });
    }

    /**
     * Starts the stress test by dispatching concurrent requests.
     */
    private function startTest(): void
    {
        $this->metrics->startTimer();

        for ($requestId = 1; $requestId <= $this->config->getRequestCount(); $requestId++) {
            $this->queue->push(1); // Ensure concurrency limit is respected

            Coroutine::create(function () use ($requestId) {
                $this->sendRequest($requestId);
            });
        }

        $this->waitForConcurrency();

        $this->metrics->endTimer();
        $this->displayReport();
    }

    /**
     * Sends a single HTTP request and handles the response.
     *
     * @param int $requestId The request ID for logging purposes.
     */
    private function sendRequest(int $requestId): void
    {
        $client = new HttpClient($this->config);
        $statusCode = $client->get();
        if ($statusCode !== null && $statusCode >= 200 && $statusCode < 300) {
            $this->metrics->incrementSuccess();
        } else {
            $this->metrics->incrementFailure();
            $this->logger->error("Request #{$requestId} failed with status code: " . ($statusCode ?? 'N/A'));
        }

        // Log progress every 100 requests
        if ($requestId % 100 === 0) {
            $this->logger->info("Dispatched {$requestId} requests...");
        }
        $this->queue->pop(); // Release the concurrent limit
    }

    /**
     * Waits for all concurrent requests to complete.
     */
    private function waitForConcurrency(): void
    {
        for ($i = 0; $i < $this->config->getConcurrency(); $i++) {
            $this->queue->push(1);
        }
    }

    /**
     * Displays the metrics report after the test finishes.
     */
    private function displayReport(): void
    {
        $duration = $this->metrics->getDuration();
        $throughput = $this->config->getRequestCount() / $duration;
        echo PHP_EOL . "--- Stress Test Metrics ---" . PHP_EOL;
        echo "Total Requests: {$this->config->getRequestCount()}" . PHP_EOL;
        echo "Successful: {$this->metrics->getSuccessCount()}" . PHP_EOL;
        echo "Failed: {$this->metrics->getFailureCount()}" . PHP_EOL;
        echo "Total Time: " . round($duration, 2) . " seconds" . PHP_EOL;
        echo "Throughput: " . round($throughput, 2) . " req/s" . PHP_EOL;
    }
}