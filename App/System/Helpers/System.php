<?php

use System\Core\HooksRoutes;
use System\Libraries\View;
use System\Request;
use System\Response;

if (!function_exists('redirect')) {
    /**
     * @param string $uri
     * @param string|null $method
     * @param int|null $code
     */
    function redirect(string $uri = "", string $method = null, int $code = null): never
    {
        if (!preg_match('#^(\w+:)?//#i', $uri)) {
            $uri = route($uri);
        }

        if (is_null($method) && isset($_SERVER['SERVER_SOFTWARE']) && str_contains($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS')) {
            $method = 'refresh';
        } elseif ($method !== 'refresh' && !is_numeric($code)) {
            if (isset($_SERVER['SERVER_PROTOCOL'], $_SERVER['REQUEST_METHOD']) && $_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.1') {
                $code = (getenv("REQUEST_METHODS") !== 'GET') ? 303 : 307;
            } else {
                $code = 302;
            }
        }

        switch ($method) {
            case 'refresh':
                header('Refresh:0;url=' . $uri);
                break;
            default:
                header('Location: ' . $uri, true, $code);
                break;
        }

        exit($code ?? 1);
    }
}

if (!function_exists("_uri_string")) {
    /**
     * @param string|array $uri
     * @return string
     */
    function _uri_string(string|array $uri): string
    {
        if (getConfig("enable_query_strings") === false) {
            is_array($uri) && $uri = implode('/', $uri);
            return ltrim($uri, '/');
        } elseif (is_array($uri)) {
            return http_build_query($uri);
        } else {
            return $uri;
        }
    }
}

if (!function_exists("route")) {
    /**
     * @param string $uri
     * @param string|null $protocol
     * @return string
     */
    function route(string $uri = "", string $protocol = null): string
    {
        $route = slash_item('route');
        if (!empty($protocol)) {
            $route = $protocol . substr($route, strpos($route, '://'));
        }

        return $route . _uri_string($uri);
    }
}

if (!function_exists("getQuery")) {
    /**
     * @param array $removeKeys
     * @param bool $hasGet
     * @return string
     */
    function getQuery(array $removeKeys = [], bool $hasGet = false): string
    {
        $query = $_SERVER['QUERY_STRING'];
        parse_str($query, $get_array);

        foreach ($removeKeys as $key) {
            if (isset($get_array[$key])) {
                unset($get_array[$key]);
            }
        }

        if ($hasGet) {
            return "&" . http_build_query($get_array);
        } else {
            return "?" . http_build_query($get_array);
        }
    }
}

if (!function_exists("assets")) {
    /**
     * @param string $file
     * @return string
     */
    function assets(string $file): string
    {
        return route(getConfig("base_dir_assets") . $file);
    }
}

if (!function_exists("slash_item")) {
    /**
     * @param string $item
     * @return null|string
     */
    function slash_item(string $item): ?string
    {
        $Config = getConfig();
        if (!isset($Config[$item])) {
            return null;
        } elseif (empty(trim($Config[$item]))) {
            return "";
        } else {
            return rtrim($Config[$item], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        }
    }
}

if (!function_exists("randomCode")) {
    /**
     * @param int $length
     * @return string
     */
    function randomCode(int $length = 8): string
    {
        $code = "";
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $len = strlen($characters);
        for ($n = 1; $n <= $length; $n++) {
            $rand = mt_rand(1, $len);
            $code .= str_shuffle($characters)[$rand - 1];
        }
        return $code;
    }
}

if (!function_exists("dateToTime")) {
    /**
     * @param string $date
     * @param string $format
     * @return bool|int|null
     */
    function dateToTime(string $date, string $format = "YYYY-MM-DD"): bool|int|null
    {
        if (strlen($date) !== strlen($format)) {
            return null;
        } else {
            switch ($format) {
                case 'YYYY/MM/DD':
                case 'YYYY-MM-DD':
                    list($y, $m, $d) = preg_split('/[-\.\/ ]/', $date);
                    break;
                case 'YYYY/DD/MM':
                case 'YYYY-DD-MM':
                    list($y, $d, $m) = preg_split('/[-\.\/ ]/', $date);
                    break;
                case 'DD-MM-YYYY':
                case 'DD/MM/YYYY':
                    list($d, $m, $y) = preg_split('/[-\.\/ ]/', $date);
                    break;

                case 'MM-DD-YYYY':
                case 'MM/DD/YYYY':
                    list($m, $d, $y) = preg_split('/[-\.\/ ]/', $date);
                    break;

                case 'YYYYMMDD':
                    $y = substr($date, 0, 4);
                    $m = substr($date, 4, 2);
                    $d = substr($date, 6, 2);
                    break;

                case 'YYYYDDMM':
                    $y = substr($date, 0, 4);
                    $d = substr($date, 4, 2);
                    $m = substr($date, 6, 2);
                    break;
                default:
                    return false;
            }

            return mktime(0, 0, 0, $m, $d, $y);
        }
    }
}

if (!function_exists("str_replace_first")) {
    /**
     * Substituir primeira ocorrencia
     * @param $from
     * @param $to
     * @param $content
     * @return null|string|string[]
     */
    function str_replace_first($from, $to, $content)
    {
        $from = '/' . preg_quote($from, '/') . '/';
        return preg_replace($from, $to, $content, 1);
    }
}

if (!function_exists('getConfig')) {
    /**
     * Get specific Config value
     * @param string|null $key
     * @return mixed
     */
    function getConfig(string $key = null): mixed
    {
        global $Config;
        return is_null($key) ? $Config : $Config[$key];
    }
}

if (!function_exists('setConfig')) {
    /**
     * Set specific Config value
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    function setConfig(string $key, mixed $value): bool
    {
        global $Config;
        return ($Config[$key] === $value);
    }
}

if (!function_exists('apiSuccessCall')) {
    /**
     * Returns an successful JSON
     * @param array $data
     * @param string $message
     * @param int $code
     * @return string
     */
    function apiSuccessCall(array $data, string $message = "", int $code = 200): string
    {
        return HooksRoutes::getInstance()->apiSuccessCallJson($data, $message, $code);
    }
}

if (!function_exists('apiErrorCall')) {
    /**
     * Returns an error JSON
     * @param array $data
     * @param string $message
     * @param int $code
     * @return string
     */
    function apiErrorCall(array $data, string $message, int $code = 404): string
    {
        return HooksRoutes::getInstance()->apiErrorCallJson($data, $message, $code);
    }
}

if (!function_exists('getDatetime')) {
    /**
     * Get date at the moment (Format for MySQL)
     * @return false|string
     */
    function getDatetime(): bool|string
    {
        return date("Y-m-d H:i:s");
    }
}

if (!function_exists('execute_callbacks')) {
    function execute_callbacks($callback, $type, $attrs = []): void
    {
        if (isset($callback[$type])) {
            if (is_array($callback[$type])) {
                foreach ($callback[$type] as $callsback) {
                    $onCallClass = $callsback[0];
                    $methodCall = $callsback[1];
                    try {
                        $verifyClass = new ReflectionClass($onCallClass);
                        $totalParams = $verifyClass->getMethod($methodCall)->getParameters();
                        $onCallInit = new $onCallClass();

                        $finalAttrs = [];
                        foreach ($totalParams as $parameter) {
                            $nameVar = $parameter->getName();
                            if (isset($attrs[$nameVar])) {
                                $finalAttrs[] = $attrs[$nameVar];
                            } else {
                                switch ($nameVar) {
                                    case 'request':
                                        $finalAttrs[] = Request::getInstance();
                                        break;
                                    case 'response':
                                        $finalAttrs[] = Response::getInstance();
                                        break;
                                }
                            }
                        }

                        call_user_func_array([$onCallInit, $methodCall], $finalAttrs);

                    } catch (ReflectionException) {
                        continue;
                    }
                }
            } else {
                $callback[$type]($callback);
            }
        }
    }
}

if (!function_exists('execute_class')) {
    function execute_class(string $class, string $method, array $parameters = []): bool
    {
        if (class_exists($class)) {
            try {
                $verifyClass = new ReflectionClass($class);
                $classParameters = $verifyClass->getMethod($method)->getParameters();
                $initClass = new $class();

                $newParameters = [];
                foreach ($classParameters as $parameter) {
                    $nameParameter = $parameter->getName();
                    if (isset($parameters[$nameParameter])) {
                        $newParameters[] = $parameters[$nameParameter];
                    } else {
                        switch ($nameParameter) {
                            case 'request':
                                $newParameters[] = Request::getInstance();
                                break;
                            case 'response':
                                $response = Response::getInstance();
                                $response->setController($initClass);
                                $newParameters[] = $response;
                                break;
                        }
                    }
                }

                $returnFunction = call_user_func_array([$initClass, $method], $newParameters);
                if ($returnFunction instanceof View) {
                    renderView($returnFunction);
                }

                return true;
            } catch (ReflectionException) {
                return false;
            }
        }
        return false;
    }
}

if (!function_exists('getUriPatch')) {
    function getUriPatch(): array|string
    {
        return str_replace([$_SERVER['QUERY_STRING'], "?", getConfig('base_dir')], "", getenv("REQUEST_URI"));
    }
}

if (!function_exists('loadFilesRoute')) {
    function loadFilesRoute(): void
    {
        $Routes = getConfig("files_route");
        foreach ($Routes as $file) {
            if (file_exists($file)) {
                include_once($file);
            } else if (file_exists(BASE_PATH . "Configs/Routes/" . $file . ".php")) {
                include_once(BASE_PATH . "Configs/Routes/" . $file . ".php");
            }
        }
    }
}

if (!function_exists('renderView')) {
    function renderView(View $view): void
    {
        if ($view->getType() === View::HTML) {
            Response::getInstance()->getController()->setView($view->getView(), $view->getParams());
        } else {
            echo $view->toJSON();
        }
    }
}
