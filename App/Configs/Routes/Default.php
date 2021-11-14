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

Routes::get("find/{id}", [
    "Controller" => Index::class,
    "Method" => "FindExample",
    "Headers" => [
        'Content-Type:' . ResponseType::CONTENT_HTML
    ],
    "RequireHeader" => [],
    "onCallBefore" => [],
    "onCallAfter" => [],
    "onCallFinish" => []
]);