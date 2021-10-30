<?php

namespace System\Core;

use System\Libraries\Lang;
use System\Libraries\Smarty;
use System\Response;
use System\ResponseType;

class DefaultErrors
{
    protected static DefaultErrors $instance;

    public static function getInstance(): DefaultErrors
    {
        self::$instance = new DefaultErrors();
        return self::$instance;
    }

    public function handlerError(string $errorNo = null, string $errorStr = null, string $errorFile = null, string $errorLine = null): void
    {
        if (ENVIRONMENT === "production") return;

        if (Response::getInstance()->getResponseHeader("Content-Type") === "application/json") {
            echo HooksRoutes::getInstance()->apiErrorCallJson($errorStr . " File: " . $errorFile . " Line: " . $errorLine, $errorNo);
            return;
        }

        echo $this->getErroHtml($errorNo, $errorStr, $errorFile, $errorLine);
    }

    public function getErroHtml($number, $error, $file, $line): string
    {
        Response::getInstance()->setHeaderType(ResponseType::CONTENT_HTML);
        
        return "<div style='display: inline-block; padding: 10px;'><div style='padding: 10px; background-color: #dd5656; border-radius: 5px; border: solid 1px #d93535; display: inline-block'>
                    <span style='font-style: italic; color: #fff'>
                    <b>\PHP Error {$number}:</b> {$error}<br>
                    <b>\File:</b> {$file}<br>
                    <b>\Line:</b> {$line}  
                    </span>
                </div></div>";
    }

    public function Error404()
    {
        global $Config;
        /*Response::getInstance()->setHeader("HTTP/1.0 404 Not Found");*/
        Response::getInstance()->setHeaderType($Config['error_content_type']);

        if (Response::getInstance()->getResponseHeader("Content-Type") === "application/json") {
            echo HooksRoutes::getInstance()->apiErrorCallJson(Lang::get("error404"), 404);
            exit();
        }

        if ($Config["template"] == TEMPLATE_ENGINE_SMARTY) {
            Smarty::getInstance()->setDefaultTemplate();
            Smarty::getInstance()->view("Error/Error404.tpl");
        } else {
            getViewPhp("Error/Error404.php");
        }
        exit();
    }

    /**
     * @param $Code
     * @param $Exception \Exception
     */
    public function ErrorXXX($Code, $Exception)
    {
        global $Config;
        Response::getInstance()->setHeader("HTTP/1.0 {$Code}");
        Response::getInstance()->setHeader("Content-Type: " . $Config['error_content_type']);

        if (Response::getInstance()->getResponseHeader("Content-Type") == "application/json") {
            echo HooksRoutes::getInstance()->apiErrorCallJson($Exception->getMessage() . " File: " . $Exception->getFile() . " Line: " . $Exception->getLine(), $Exception->getCode());
            exit();
        }
        if ($Config["template"] == TEMPLATE_ENGINE_SMARTY) {
            Smarty::getInstance()->setDefaultTemplate();
            Smarty::getInstance()->view("Error/ErrorXXX.tpl", ["Excpetion" => $Exception]);
        } else {
            getViewPhp("Error/ErrorXXX.php", ["Excpetion" => $Exception]);
        }
        exit();
    }
}