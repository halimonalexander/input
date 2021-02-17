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
                return $this->httpHeaders['AUTHORIZATION'];

            default:
                return null;
        }
    }
}
