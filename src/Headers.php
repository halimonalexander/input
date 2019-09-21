<?php
namespace HalimonAlexander\Input;

class Headers
{
    /**
     * @var array
     */
    private $headers;

    /**
     * @var array
     */
    private $httpHeaders = [];

    public function __construct()
    {
        $this->headers = $_SERVER;

        if (function_exists('getallheaders')) {
            $httpHeaders = \getallheaders();
            if ($httpHeaders !== false) {
                foreach ($httpHeaders as $key => $value)
                    $this->httpHeaders[strtoupper($key)] = $value;
            }
        }
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->headers;
    }

    /**
     * @return string|null
     */
    public function auth(): ?string
    {
        if (array_key_exists('AUTHORIZATION', $this->headers)) {
            return $this->headers['AUTHORIZATION'];
        } elseif (array_key_exists('HTTP_AUTHORIZATION', $this->headers)) {
            return $this->headers['HTTP_AUTHORIZATION'];
        } elseif (array_key_exists('AUTHORIZATION', $this->httpHeaders)) {
            return $this->httpHeaders['AUTHORIZATION'];
        } elseif (array_key_exists('HTTP_AUTHORIZATION', $this->httpHeaders)) {
            return $this->httpHeaders['AUTHORIZATION'];
        } else {
            return null;
        }
    }
}
