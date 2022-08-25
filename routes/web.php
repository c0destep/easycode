<?php

use App\Controllers\IndexController;
use Easycode\Routing\Route;

Route::get('/', [IndexController::class, 'index'], [
    'Content-Type' => 'text/html charset=UTF-8'
]);