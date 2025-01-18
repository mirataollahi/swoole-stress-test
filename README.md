# Swoole HTTP Stress Tester

A PHP-based HTTP client stress tester built with Swoole. This package allows you to send concurrent requests to an HTTP server for performance testing.

## Features

- **Concurrency**: Supports configurable concurrency for sending multiple requests simultaneously.
- **Request Count**: Allows defining the number of requests to be sent in the stress test.
- **Timeouts**: Customizable request timeout settings.
- **SSL Handling**: Option to disable SSL verification for HTTP requests.
- **Performance Metrics**: Tracks success, failure, throughput, and duration of the requests.

## Requirements

- PHP >= 8.2
- Swoole extension >= 4.5.0
- Composer

## Installation

### Step 1: Install the dependencies

Install the project via Composer:

```bash
 composer require craftix/requester
```


## Usage

### Run stress test

```php
use Craftix\Requester\Config;
use Craftix\Requester\Requester;


$config = Config::create()
    ->setHost('127.0.0.1') // The target host to send requests to (e.g., 'google.com')
    ->setPort(8040) // The target port to use for requests (default is 443 for HTTPS)
    ->setPath('/') // The path for the HTTP request (e.g., '/' for the root page)
    ->setRequestCount(1000) // The total number of requests to send during the stress test
    ->setConcurrency(50) // The number of concurrent requests to send at a time
    ->setRequestTimeout(2) // The timeout duration (in seconds) for each request
    ->disableSsl(); // Disable SSL verification (useful for testing against servers with invalid SSL certificates)


/**
 * Run Stress Test
 */
Requester::run($config);
```

