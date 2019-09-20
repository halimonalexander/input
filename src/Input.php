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
    const TYPE_BOOL    = 'bool';
    const TYPE_INT     = 'int';
    const TYPE_STRING  = 'string';

    /**
     * @return mixed
     */
    public function get(string $field, string $type = self::TYPE_STRING, bool $emptyAsNull = true)
    {
        $value = $this->extract($_GET, $field, $type);

        return $this->process($value, $emptyAsNull);
    }
    
    /**
     * @return mixed
     */
    public function post(string $field, string $type = self::TYPE_STRING, bool $emptyAsNull = true)
    {
        $value = $this->extract($this->getIncomingData(), $field, $type);

        return $this->process($value, $emptyAsNull);
    }

    private function getIncomingData()
    {
        $contentType = $_SERVER["CONTENT_TYPE"];
        if ($contentType == "multipart/form-data" || $contentType == "application/x-www-form-urlencoded")
            return $_POST;

        if ($contentType == "application/json")
            return json_decode($this->rawPost(), true);

        return $this->rawPost();
    }

    private function rawPost()
    {
        return file_get_contents("php://input");
    }

    /**
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

        if ($type == $this::TYPE_BOOL) {
            return (bool) $value;
        } elseif ($type == $this::TYPE_INT) {
            return (int) $value;
        } elseif ($type == $this::TYPE_STRING) {
            return (string) $value;
        }
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