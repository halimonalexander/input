<?php

/*
 * This file is part of Input.
 *
 * (c) Halimon Alexander <vvthanatos@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace HalimonAlexander\Input;

class Input
{
    public const TYPE_BOOL   = 'bool';
    public const TYPE_INT    = 'int';
    public const TYPE_STRING = 'string';

    private Headers $headers;

    public function __construct()
    {
        $this->headers = new Headers();
    }

    public function headers(): Headers
    {
        return $this->headers;
    }

    /**
     * @param string $field
     * @param string $type
     * @param bool $emptyAsNull
     *
     * @return mixed
     */
    public function get(string $field, string $type = self::TYPE_STRING, bool $emptyAsNull = true)
    {
        $value = $this->extract($_GET, $field, $type);

        return $this->process($value, $emptyAsNull);
    }

    /**
     * @param string $field
     * @param string $type
     * @param bool $emptyAsNull
     *
     * @return mixed
     */
    public function post(string $field, string $type = self::TYPE_STRING, bool $emptyAsNull = true)
    {
        $value = $this->extract($this->getIncomingData(), $field, $type);

        return $this->process($value, $emptyAsNull);
    }

    private function getIncomingData(): array
    {
        $contentType = $_SERVER["CONTENT_TYPE"];
        if ($contentType === "multipart/form-data" || $contentType === "application/x-www-form-urlencoded") {
            return $_POST;
        }

        if ($contentType === "application/json") {
            try {
                return json_decode($this->rawPost(), true, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                return [];
            }
        }

        return [$this->rawPost()];
    }

    private function rawPost(): string
    {
        $input = file_get_contents("php://input");

        if ($input === false) {
            return '{}';
        }

        return $input;
    }

    /**
     * @param array $source
     * @param string $field
     * @param string $type
     *
     * @return bool|int|string|null
     */
    private function extract(array $source, string $field, string $type)
    {
        if (empty($field)) {
            return null;
        }

        if (!isset($source[$field])) {
            return null;
        }

        $value = trim($source[$field]);

        if ($type === $this::TYPE_BOOL) {
            return (bool) $value;
        }
        if ($type === $this::TYPE_INT) {
            return (int) $value;
        }

        return $value;
    }

    /**
     * @param mixed $value
     * @param bool $emptyAsNull
     * @return null|mixed
     */
    private function process($value, bool $emptyAsNull)
    {
        if (empty($value) && $emptyAsNull === true) {
            return null;
        }

        return $value;
    }
}
