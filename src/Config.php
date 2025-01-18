<?php

namespace Craftix\Requester;

class Config
{
    private int $requestCount = 10;
    private int $concurrency = 10;
    private string $path;
    private string $host;
    private int $port;
    private bool $ssl = false;
    private int $requestTimeout = 3;

    public static function create(): self
    {
        return new self();
    }
    public function getRequestCount(): int
    {
        return $this->requestCount;
    }

    public function setRequestCount(int $requestCount): self
    {
        $this->requestCount = $requestCount;
        return $this;
    }

    public function getConcurrency(): int
    {
        return $this->concurrency;
    }

    public function setConcurrency(int $concurrency): self
    {
        $this->concurrency = $concurrency;
        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function setHost(string $host): self
    {
        $this->host = $host;
        return $this;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function setPort(int $port): self
    {
        $this->port = $port;
        return $this;
    }

    public function isSsl(): bool
    {
        return $this->ssl;
    }

    public function enableSsl(): self
    {
        $this->ssl = true;
        return $this;
    }

    public function disableSsl(): self
    {
        $this->ssl = false;
        return $this;
    }

    public function getRequestTimeout(): int
    {
        return $this->requestTimeout;
    }

    public function setRequestTimeout(int $requestTimeout): self
    {
        $this->requestTimeout = $requestTimeout;
        return $this;
    }
}
