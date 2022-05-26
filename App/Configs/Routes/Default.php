<?php

use Controller\IndexController;
use System\Core\Routes;
use System\ResponseType;

Routes::get("/", [
    "Controller" => IndexController::class,
    "Method" => "Index",
    "Headers" => [
        'Content-Type' => ResponseType::CONTENT_HTML
    ],
    "RequireHeader" => [],
    "onCallBefore" => [],
    "onCallAfter" => [],
    "onCallFinish" => []
]);

Routes::get("find/{id}", [
    "Controller" => IndexController::class,
    "Method" => "FindUser",
    "Headers" => [
        'Content-Type' => ResponseType::CONTENT_HTML
    ],
    "RequireHeader" => [],
    "onCallBefore" => [],
    "onCallAfter" => [],
    "onCallFinish" => []
]);