<?php

namespace System;

use System\Core\Controller;
use System\Libraries\View;
use System\Libraries\ViewHtml;
use System\Libraries\ViewJson;
use System\ResponseType as ResponseType;

class Response
{
    protected static ?Response $instance = null;
    protected array $responseHeader = array();
    protected ?Controller $controller = null;

    const ALL = "ALL";
    const GET = "GET";
    const POST = "POST";
    const PUT = "PUT";
    const DELETE = "DELETE";
    const PATCH = "PATCH";
    const OPTIONS = "OPTIONS";
    const HEAD = "HEAD";

    /**
     * @return null|Response
     */
    public static function getInstance(): ?Response
    {
        if (is_null(self::$instance)) self::$instance = new Response();
        return self::$instance;
    }

    /**
     * @param Controller $controller
     */
    public function setController(Controller $controller)
    {
        $this->controller = $controller;
    }

    /**
     * @return Controller|null $controller
     */
    public function getController(): ?Controller
    {
        return $this->controller;
    }

    /**
     * @param string $key
     * @param string|null $value
     */
    public function setHeader(string $key, string $value = null)
    {
        if (is_null($value)) {
            $Get = explode(":", $key);
            $this->responseHeader[$Get[0]] = $Get[1] ?? null;
            header($key);
        } else {
            $this->responseHeader[$key] = $value;
            header("$key:$value");
        }
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getResponseHeader(string $key): mixed
    {
        return $this->responseHeader[$key];
    }

    /**
     * @param string $type
     */
    public function setHeaderType(string $type)
    {
        $this->setHeader("Content-Type", $type);
    }

    /**
     * @return ViewJson
     */
    public function json(): ViewJson
    {
        $this->setHeaderType(ResponseType::CONTENT_JSON);
        return View::getJson();
    }

    /**
     * @return ViewHtml
     */
    public function html(): ViewHtml
    {
        $this->setHeaderType(ResponseType::CONTENT_HTML);
        return View::getHtml();
    }

    /**
     * Get Default HTML
     * @param array $Merge Extra Headers
     * @return array
     */
    public static function getDefaultHtml(array $Merge = array()): array
    {
        return array_merge($Merge, [
            "Content-Type:" . ResponseType::CONTENT_HTML,
        ]);
    }

    /**
     * Get Default JSON
     * @param array $Merge Extra Headers
     * @return array
     */
    public static function getDefaultJson(array $Merge = array()): array
    {
        return array_merge($Merge, [
            "Content-Type:" . ResponseType::CONTENT_JSON,
        ]);
    }

    /**
     * Get Default JS
     * @param array $Merge Extra Headers
     * @return array
     */
    public static function getDefaultJs(array $Merge = array()): array
    {
        return array_merge($Merge, [
            "Content-Type:" . ResponseType::CONTENT_JS,
        ]);
    }

    /**
     * Get Default CSS
     * @param array $Merge Extra Headers
     * @return array
     */
    public static function getDefaultCss(array $Merge = array()): array
    {
        return array_merge($Merge, [
            "Content-Type:" . ResponseType::CONTENT_CSS,
        ]);
    }

    /**
     * Get Default XML
     * @param array $Merge Extra Headers
     * @return array
     */
    public static function getDefaultXml(array $Merge = array()): array
    {
        return array_merge($Merge, [
            "Content-Type:" . ResponseType::CONTENT_XML,
        ]);
    }

    /**
     * Get Default OctetStream
     * @param array $Merge Extra Headers
     * @return array
     */
    public static function getDefaultOctetStream(array $Merge = array()): array
    {
        return array_merge($Merge, [
            "Content-Type:" . ResponseType::CONTENT_OCTETSTREAM,
        ]);
    }
}