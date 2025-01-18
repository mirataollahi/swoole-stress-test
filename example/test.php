<?php

use Craftix\Requester\Config;
use Craftix\Requester\Requester;

require_once __DIR__ . '/../vendor/autoload.php';


/**
 * Stress Test Configuration
 *
 * This configuration sets up the parameters for running a stress test on an HTTP server.
 * You can customize the host, port, path, the number of requests, concurrency level,
 * request timeout, and whether to disable SSL verification.
 */
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
 * This initiates the stress test using the configured settings.
 */
Requester::run($config);