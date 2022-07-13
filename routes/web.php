<?php

use App\Controllers\IndexController;
use System\Core\Routes;
use System\Response;
use System\ResponseType;

Routes::group(Response::GET, "", [
    "" => [
        "method" => "index"
    ]
], [
    "controller" => IndexController::class,
    "headers" => [
        "Content-Type" => ResponseType::CONTENT_HTML
    ],
    "requireHeaders" => [
        "Content-Type" => ResponseType::CONTENT_HTML
    ],
    "onCallBefore" => [],
    "onCallAfter" => [],
    "onCallFinish" => []
]);