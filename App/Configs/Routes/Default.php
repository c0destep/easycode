<?php

use Controller\IndexController;
use System\Core\Routes;
use System\Response;
use System\ResponseType;

Routes::group(Response::GET, '/', [
    '' => [
        'Controller' => IndexController::class,
        'Method' => 'Index',
        "Headers" => [
            'Content-Type' => ResponseType::CONTENT_HTML
        ]
    ],
    'find/{id}' => [
        'Controller' => IndexController::class,
        'Method' => 'FindUser',
        "Headers" => [
            'Content-Type' => ResponseType::CONTENT_HTML
        ]
    ]
], [
    "RequireHeader" => [
        'Content-Type' => ResponseType::CONTENT_HTML
    ],
    "onCallBefore" => [],
    "onCallAfter" => [],
    "onCallFinish" => []
]);

Routes::post('/', ['Controller' => IndexController::class, 'Method' => 'Upload']);