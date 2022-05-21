<?php

namespace System;

use System\Core\Controller;
use System\Libraries\View;
use System\Libraries\ViewHtml;
use System\Libraries\ViewJson;
use System\ResponseType as ResponseType;

class Response
{
    const ALL = "ALL";
    const GET = "GET";
    const POST = "POST";
    const PUT = "PUT";
    const DELETE = "DELETE";
    const PATCH = "PATCH";
    const OPTIONS = "OPTIONS";
    const HEAD = "HEAD";
    protected static ?Response $instance = null;
    protected array $responseHeader = array();
    protected ?Controller $controller = null;

    /**
     * @return null|Response
     */
    public static function getInstance(): ?Response
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get Default HTML
     * @param array $Merge Extra Headers
     * @return array
     */
    public static function getDefaultHtml(array $Merge = array()): array
    {
        return array_merge($Merge, [
            "Content-Type" => ResponseType::CONTENT_HTML,
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
            "Content-Type" => ResponseType::CONTENT_JSON,
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
            "Content-Type" => ResponseType::CONTENT_JS,
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
            "Content-Type" => ResponseType::CONTENT_CSS,
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
            "Content-Type" => ResponseType::CONTENT_XML,
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
            "Content-Type" => ResponseType::CONTENT_OCTETSTREAM,
        ]);
    }

    /**
     * @return Controller|null $controller
     */
    public function getController(): ?Controller
    {
        return $this->controller ?? null;
    }

    /**
     * @param Controller $controller
     */
    public function setController(Controller $controller): void
    {
        $this->controller = $controller;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getResponseHeader(string $key): mixed
    {
        return $this->responseHeader[$key] ?? null;
    }

    /**
     * @return ViewJson
     */
    public function json(): ViewJson
    {
        $this->setContentType(ResponseType::CONTENT_JSON);
        return View::getJson();
    }

    /**
     * @param string $type
     */
    public function setContentType(string $type): void
    {
        $this->setHeader("Content-Type", $type);
    }

    /**
     * @param string $key
     * @param string|null $value
     */
    public function setHeader(string $key, string $value = null): void
    {
        if (is_null($value)) {
            $this->responseHeader[$key] = null;
            header($key);
        } else {
            $this->responseHeader[$key] = $value;
            header("$key:$value");
        }
    }

    /**
     * @return ViewHtml
     */
    public function html(): ViewHtml
    {
        $this->setContentType(ResponseType::CONTENT_HTML);
        return View::getHtml();
    }
}