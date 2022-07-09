<?php

namespace System\Core;

use System\Libraries\Smarty;
use System\Response;
use System\ResponseType;

class DefaultErrors
{
    private static DefaultErrors $instance;

    public function handlerError(string $errorNo = null, string $errorStr = null, string $errorFile = null, string $errorLine = null): void
    {
        /*if (ENVIRONMENT === "production") {
            return;
        }*/

        if (Response::getInstance()->getResponseHeader("Content-Type") === ResponseType::CONTENT_JSON) {
            echo HooksRoutes::getInstance()->apiErrorCallJson(message: $errorStr . " File: " . $errorFile . " Line: " . $errorLine, responseCode: $errorNo);
            return;
        }

        echo $this->getErrorHtml($errorNo, $errorStr, $errorFile, $errorLine);
    }

    public static function getInstance(): DefaultErrors
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getErrorHtml(string $number, string $error, string $file, string $line): string
    {
        Response::getInstance()->setContentType(ResponseType::CONTENT_HTML);

        return "<div style='display: inline-block; padding: 10px;'><div style='padding: 10px; background-color: #dd5656; border-radius: 5px; border: solid 1px #d93535; display: inline-block'>
                    <span style='font-style: italic; color: #fff'>
                    <b>\PHP Error {$number}:</b> {$error}<br>
                    <b>\File:</b> {$file}<br>
                    <b>\Line:</b> {$line}  
                    </span>
                </div></div>";
    }

    public function Error404(): never
    {
        global $Config;
        Response::getInstance()->setHeader("HTTP/1.0 404 Not Found");
        Response::getInstance()->setContentType($Config['error_content_type']);

        if (Response::getInstance()->getResponseHeader("Content-Type") === ResponseType::CONTENT_JSON) {
            echo HooksRoutes::getInstance()->apiErrorCallJson(message: "Not Found");
            exit(404);
        }

        Smarty::getInstance()->setDefaultTemplate();
        Smarty::getInstance()->view("Error/Error404.tpl");

        exit(404);
    }

    /**
     * @param int|string $code
     * @param object $exception
     */
    public function ErrorXXX(int|string $code, object $exception): never
    {
        global $Config;
        Response::getInstance()->setHeader("HTTP/1.0 {$code}");
        Response::getInstance()->setContentType($Config['error_content_type']);

        if (Response::getInstance()->getResponseHeader("Content-Type") === "application/json") {
            echo HooksRoutes::getInstance()->apiErrorCallJson(message: $exception->getMessage() . " File: " . $exception->getFile() . " Line: " . $exception->getLine(), responseCode: $exception->getCode());
            exit($code);
        }

        Smarty::getInstance()->setDefaultTemplate();
        Smarty::getInstance()->view("Error/ErrorXXX.tpl", ["Exception" => $exception]);

        exit($code);
    }
}