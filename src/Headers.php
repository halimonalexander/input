<?php

/*
 * This file is part of Input.
 *
` * (c) Halimon Alexander <a@halimon.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace HalimonAlexander\Input;

class Headers
{
    private array $headers;
    private array $httpHeaders = [];

    public function __construct()
    {
        $this->headers = $_SERVER;

        if (function_exists('getallheaders')) {
            $httpHeaders = \getallheaders();
            if ($httpHeaders !== false) {
                foreach ($httpHeaders as $key => $value) {
                    $this->httpHeaders[strtoupper($key)] = $value;
                }
            }
        }
    }

    public function all(): array
    {
        return $this->headers;
    }

    public function auth(): ?string
    {
        switch (true) {
            case array_key_exists('AUTHORIZATION', $this->headers):
                return $this->headers['AUTHORIZATION'];

            case array_key_exists('HTTP_AUTHORIZATION', $this->headers):
                return $this->headers['HTTP_AUTHORIZATION'];

            case array_key_exists('AUTHORIZATION', $this->httpHeaders):
                return $this->httpHeaders['AUTHORIZATION'];

            case array_key_exists('HTTP_AUTHORIZATION', $this->httpHeaders):
                return $this->httpHeaders['HTTP_AUTHORIZATION'];

            default:
                return null;
        }
    }

    public function getUri(): string
    {
        return $_SERVER['REQUEST_URI'];
    }

    public function getProtocol(): string
    {
        return $_SERVER['SERVER_PROTOCOL'];
    }

    /**
     * Get the request method used, taking overrides into account.
     *
     * @return string The Request method to handle
     */
    public function getRequestMethod(): string
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'POST' && $this->hasMethodOverride() && $this->isValidMethodOverride()) {
            return $this->getMethodOverride();
        }

        return $method;
    }

    private function hasMethodOverride(): bool
    {
        $headers = !empty($this->httpHeaders) ? $this->httpHeaders : $_SERVER;

        return isset($headers['X-HTTP-Method-Override']);
    }

    private function isValidMethodOverride(): bool
    {
        return in_array($this->getMethodOverride(), ['PUT', 'DELETE', 'PATCH']);
    }

    private function getMethodOverride(): string
    {
        $headers = !empty($this->httpHeaders) ? $this->httpHeaders : $_SERVER;

        return $headers['X-HTTP-Method-Override'];
    }
}
