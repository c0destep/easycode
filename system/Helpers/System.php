<?php

use System\Core\HooksRoutes;
use System\FastApp;
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

if (!function_exists("route")) {
    /**
     * @param string $uri
     * @return string
     */
    function route(string $uri = ''): string
    {
        return FastApp::getInstance()->route(trim($uri));
    }
}

if (!function_exists("assets")) {
    /**
     * @param string $file
     * @return string
     */
    function assets(string $file): string
    {
        return FastApp::getInstance()->assets(trim($file));
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
                        if ($nameParameter == 'request') {
                            $newParameters[] = Request::getInstance();
                        }

                        Response::getInstance()->setController($initClass);
                    }
                }

                $returnFunction = call_user_func_array([$initClass, $method], $newParameters);
                if ($returnFunction instanceof View) {
                    renderView($returnFunction);
                }

                return true;
            } catch (ReflectionException $reflectionException) {
                die($reflectionException);
            } catch (Exception $e) {
                die($e);
            }
        }
        return false;
    }
}

if (!function_exists('renderView')) {
    /**
     * @throws Exception
     */
    function renderView(View $view): void
    {
        if ($view->getType() === View::HTML) {
            Response::getInstance()->getController()->setView($view->getView(), $view->getParams());
        } else {
            echo $view->toJSON();
        }
    }
}

if (!function_exists('__')) {
    /**
     * @throws Exception
     */
    function __(string $keyName, array $values = []): string
    {
        return FastApp::getInstance()->k($keyName, $values);
    }
}

if (!function_exists('response')) {
    function response(): Response
    {
        return Response::getInstance();
    }
}
