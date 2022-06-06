<?php

namespace System;

class Request
{
    const GET = "GET";
    const HEAD = "HEAD";
    const POST = "POST";
    const PUT = "PUT";
    const DELETE = "DELETE";
    const OPTIONS = "OPTIONS";

    protected static Request $instance;
    protected array $headers;

    /**
     * Response constructor.
     */
    public function __construct()
    {
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
     * @param string $input
     * @return mixed
     */
    public function __get(string $input): mixed
    {
        return self::input($input);
    }

    /**
     * @param string $input
     * @param mixed|null $default
     * @return mixed
     */
    public static function input(string $input, mixed $default = null): mixed
    {
        return match (filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_ENCODED)) {
            self::GET => $_GET[$input] ?? $default,
            self::POST, self::DELETE, self::PUT => $_POST[$input] ?? $default,
            $default => null
        };
    }
}