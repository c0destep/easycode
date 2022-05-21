<?php

namespace System;

class Request
{
    const GET = "GET";
    const POST = "POST";
    const REQUEST = "REQUEST";
    const JSON = "JSON";
    const EXTRA = "EXTRA";

    protected static Request $instance;
    protected static mixed $paramJson;
    protected static array $extra;
    protected array|false $headers;

    /**
     * Response constructor.
     */
    public function __construct()
    {
        self::$paramJson = getJsonPost();
        $this->headers = getAllHeaders();
    }

    /**
     * @return Request
     */
    public static function getInstance(): Request
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * XSS clear
     */
    public static function xssClear(): void
    {
        $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    }

    /**
     * @param string $key
     * @param bool $xss
     * @return mixed
     */
    public static function get(string $key, bool $xss = false): mixed
    {
        if (!isset($_GET[$key])) return null;
        if ($xss) return filter_input(INPUT_GET, $key, FILTER_SANITIZE_STRING);
        return $_GET[$key];
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getDefault(string $key, mixed $default): mixed
    {
        if (!isset($_GET[$key])) return $default;
        return $_GET[$key];
    }

    /**
     * @param string $key
     * @param bool $xss
     * @return mixed
     */
    public static function post(string $key, bool $xss = false): mixed
    {
        if (!isset($_POST[$key])) return null;
        if ($xss) return filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING);
        return $_POST[$key];
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function postDefault(string $key, mixed $default): mixed
    {
        if (!isset($_POST[$key])) return $default;
        return $_POST[$key];
    }

    /**
     * @param null $key
     * @param bool $xss
     * @return mixed
     */
    public static function json($key = null, bool $xss = false): mixed
    {
        if ($key == null) {
            return self::$paramJson;
        }
        if (!isset(self::$paramJson[$key]))
            return null;

        if ($xss)
            return filter_var(self::$paramJson[$key], FILTER_SANITIZE_STRING);

        return self::$paramJson[$key];
    }

    /**
     * @param string|null $key
     * @return mixed
     */
    public static function extra(string $key = null): mixed
    {
        if (is_null($key)) return self::$extra;
        return self::$extra[$key];
    }

    /**
     * @param array $array
     */
    public static function setExtra(array $array): void
    {
        self::$extra = $array;
    }

    /**
     * @param string|null $key
     * @return string|array|null
     */
    public function getHeader(string $key = null): string|array|null
    {
        if (is_null($key)) {
            return $this->headers;
        } elseif (isset($this->headers[$key])) {
            return $this->headers[$key];
        } else {
            return null;
        }
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function __get(string $key): mixed
    {
        return self::find($key);
    }

    /**
     * @param $key string
     * @return mixed
     */
    public static function find(string $key): mixed
    {
        if (isset($_GET[$key])) return filter_var($_GET[$key], FILTER_SANITIZE_STRING);
        if (isset($_POST[$key])) return filter_var($_POST[$key], FILTER_SANITIZE_STRING);
        if (isset(self::$paramJson[$key])) return self::$paramJson[$key];
        if (isset(self::$extra[$key])) return self::$extra[$key];
        if (isset($_REQUEST[$key])) return filter_var($_REQUEST[$key], FILTER_SANITIZE_STRING);
        return null;
    }
}