<?php
namespace HalimonAlexander\Input;

class Headers
{
    /**
     * @var array
     */
    private $headers;

    public function __construct()
    {
        $this->headers = $_SERVER;
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
        } else {
            return null;
        }
    }
}
