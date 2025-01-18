<?php declare(strict_types=1);

namespace Craftix\Requester\Client;

use Craftix\Requester\Config;
use Craftix\Requester\Logger\Logger;
use Swoole\Coroutine\Http\Client;
use Throwable;


class HttpClient
{
    private Client $client;
    public Config $config;
    public Logger $logger;

    public function __construct(Config $config)
    {
        $this->logger = new Logger();
        $this->config = $config;
        $this->client = new Client(
            $this->config->getHost(),
            $this->config->getPort(),
            $this->config->isSsl()
        );
        $this->client->set([
            'timeout'    => $this->config->getRequestTimeout(),
            'keep_alive' => false,
        ]);
    }

    public function get(): ?int
    {
        try {
            $this->client->get($this->config->getPath());
            return $this->client->statusCode;
        } catch (Throwable $e) {
            $this->logger->error("Http request failed : {$e->getMessage()}");
            return null;
        } finally {
            $this->client->close();
        }
    }
}