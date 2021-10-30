<?php

use Controller\Index;
use System\Core\Routes;
use System\ResponseType;

Routes::get("/", [
    "Controller" => Index::class,
    "Method" => "Index",
    "Headers" => [
        'Content-Type:' . ResponseType::CONTENT_HTML
    ],
    "RequireHeader" => [],
    "onCallBefore" => [],
    "onCallAfter" => [],
    "onCallFinish" => []
]);