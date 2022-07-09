<?php

namespace System\Core;

use Exception;
use JetBrains\PhpStorm\NoReturn;
use System\Libraries\Lang;
use System\Response;
use System\ResponseType;

class HooksRoutes
{
    private static ?HooksRoutes $instance = null;

    /**
     * @param string $message
     * @throws Exception
     */
    public function onCallError(string $message): void
    {
        $Header = Response::getInstance()->getResponseHeader("Content-Type");
        if ($Header === ResponseType::CONTENT_JSON) {
            echo $this->apiErrorCallJson([], $message);
            exit();
        } else {
            throw new Exception($message, 99);
        }
    }

    public static function getInstance(): ?HooksRoutes
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function apiErrorCallJson(array $data = [], string $message = "", int $responseCode = 404): string
    {
        $json = array();
        $json['responseCode'] = $responseCode;
        $json['response'] = $data;
        $json['hasError'] = true;
        $json['message'] = $message;
        $json['time'] = time();
        return is_bool(json_encode($json)) ? "" : json_encode($json);
    }

    public function onCallSuccess(string $message): bool|string
    {
        $Header = Response::getInstance()->getResponseHeader("Content-Type");
        if ($Header === ResponseType::CONTENT_JSON) {
            return $this->apiSuccessCallJson([], $message);
        } else {
            return $message;
        }
    }

    public function apiSuccessCallJson(array $data = [], string $message = "", int $responseCode = 200): string
    {
        $json = array();
        $json['responseCode'] = $responseCode;
        $json['response'] = $data;
        $json['hasError'] = false;
        $json['message'] = $message;
        $json['time'] = time();
        return is_bool(json_encode($json)) ? "" : json_encode($json);
    }

    #[NoReturn] public function onNotFound(): void
    {
        global $Config;
        Response::getInstance()->setHeader("Content-Type: " . $Config['error_content_type']);
        foreach ($Config['error_extra_headers'] as $header) {
            Response::getInstance()->setHeader($header);
        }

        if ($Config['error_content_type'] === ResponseType::CONTENT_JSON) {
            echo HooksRoutes::apiErrorCallJson(Lang::get("error404"), 404);
            exit();
        } else {
            DefaultErrors::getInstance()->Error404();
        }
    }
}