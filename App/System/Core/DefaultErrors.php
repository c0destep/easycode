<?php

namespace System\Core;

use JetBrains\PhpStorm\NoReturn;
use System\Libraries\Lang;
use System\Libraries\Smarty;
use System\Response;
use System\ResponseType;

class DefaultErrors
{
    protected static DefaultErrors $instance;

    public function handlerError(string $errorNo = null, string $errorStr = null, string $errorFile = null, string $errorLine = null): void
    {
        if (ENVIRONMENT === "production") {
            return;
        }

        if (Response::getInstance()->getResponseHeader("Content-Type") === ResponseType::CONTENT_JSON) {
            echo HooksRoutes::getInstance()->apiErrorCallJson($errorStr . " File: " . $errorFile . " Line: " . $errorLine, $errorNo);
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

    #[NoReturn] public function Error404(): void
    {
        global $Config;
        Response::getInstance()->setHeader("HTTP/1.0 404 Not Found");
        Response::getInstance()->setContentType($Config['error_content_type']);

        if (Response::getInstance()->getResponseHeader("Content-Type") === ResponseType::CONTENT_JSON) {
            echo HooksRoutes::getInstance()->apiErrorCallJson(Lang::get("error404"), 404);
            exit(404);
        }

        if ($Config["template"] === TEMPLATE_ENGINE_SMARTY) {
            Smarty::getInstance()->setDefaultTemplate();
            Smarty::getInstance()->view("Error/Error404.tpl");
        } else {
            getViewPhp("Error/Error404.php");
        }
        exit(404);
    }

    /**
     * @param int|string $code
     * @param object $exception
     */
    #[NoReturn] public function ErrorXXX(int|string $code, object $exception): void
    {
        global $Config;
        Response::getInstance()->setHeader("HTTP/1.0 {$code}");
        Response::getInstance()->setContentType($Config['error_content_type']);

        if (Response::getInstance()->getResponseHeader("Content-Type") === "application/json") {
            echo HooksRoutes::getInstance()->apiErrorCallJson($exception->getMessage() . " File: " . $exception->getFile() . " Line: " . $exception->getLine(), $exception->getCode());
            exit();
        }

        if ($Config["template"] === TEMPLATE_ENGINE_SMARTY) {
            Smarty::getInstance()->setDefaultTemplate();
            Smarty::getInstance()->view("Error/ErrorXXX.tpl", ["Exception" => $exception]);
        } else {
            getViewPhp("Error/ErrorXXX.php", ["Exception" => $exception]);
        }
        exit();
    }
}