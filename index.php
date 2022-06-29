<?php

require_once 'vendor/autoload.php';

use Dotenv\Dotenv;
use System\FastApp;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$dotenv->required('VERSION')->notEmpty();
$dotenv->required('APP_ENV')->allowedValues(['development', 'production']);
$dotenv->required('DB_ACTIVE')->isBoolean();
$dotenv->ifPresent('DB_DRIVER')->notEmpty();
$dotenv->ifPresent('DB_HOST')->notEmpty();
$dotenv->ifPresent('DB_NAME')->notEmpty();
$dotenv->ifPresent('DB_USER')->notEmpty();

const VERSION = 'v0.5.7-dev';
const ENVIRONMENT = 'development'; // Production our Development
const ROOT_PATH = __DIR__;
const BASE_PATH = ROOT_PATH . '/App/';
const BASE_PATH_CACHE = __DIR__ . '/App/Cache/';
const BASE_PATH_THIRD = __DIR__ . '/App/Third/';
const BASE_PATH_MODELS = __DIR__ . '/App/Models/';
const BASE_PATH_VIEWS = __DIR__ . '/App/Views/';
const BASE_PATH_MODULES = __DIR__ . '/App/Modules/';
const TEMPLATE_ENGINE_SMARTY = 'smarty';

switch ($_ENV['APP_ENV']) {
    case 'development':
        error_reporting(-1);
        ini_set('display_errors', true);
        break;
    case 'production':
        ini_set('display_errors', false);
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
        break;
    default:
        header('HTTP/1.1 503 Service Unavailable.', true, 503);
        echo 'The application environment is not set correctly.';
        exit(1);
}

require_once 'App/System/Core/Functions/DefaultFunctions.php';

set_error_handler('handler_error');
set_exception_handler('handler_exception');
spl_autoload_register('loaderFastApp');
register_shutdown_function('shutdownHandler');

require_once 'App/Configs/Config.php';
require_once 'App/Configs/Hooks.php';

date_default_timezone_set($GLOBALS['Config']['timezone']);

$App = new FastApp();
