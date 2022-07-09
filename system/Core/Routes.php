<?php

namespace System\Core;

use Exception;
use System\Request;
use System\Response;

class Routes
{
    protected static array $routes = [];
    protected static array $dynamicRoutes = [];

    /**
     * @param string $route
     * @param array $settings
     */
    public static function any(string $route, array $settings): void
    {
        self::other(Response::ALL, $route, $settings);
    }

    /**
     * @param string $type
     * @param string $route
     * @param array $settings
     */
    public static function other(string $type, string $route, array $settings): void
    {
        ["controller" => $controller, "method" => $method, "headers" => $headers, "requireHeaders" => $requireHeaders, "onCallBefore" => $onCallBefore, "onCallAfter" => $onCallAfter, "onCallFinish" => $onCallFinish] = $settings;
        self::simple($type, $route, $controller, $method, $headers ?? [], $requireHeaders ?? [], $onCallBefore ?? [], $onCallAfter ?? [], $onCallFinish ?? []);
    }

    /**
     * @param string $type Type Request
     * @param string $route Route URL
     * @param string $controller Controller
     * @param string $method Function
     * @param array $headers Headers http response
     * @param array $requireHeaders Headers http in request
     * @param array $onCallBefore Call on Before method controller
     * @param array $onCallAfter Call on After method controller
     * @param array $onCallFinish Call on Finish controller
     */
    public static function simple(string $type, string $route, string $controller, string $method, array $headers = [], array $requireHeaders = [], array $onCallBefore = [], array $onCallAfter = [], array $onCallFinish = []): void
    {
        self::setRoute($type, $route, [
            "controller" => $controller,
            "method" => $method,
            "headers" => $headers,
            "requireHeaders" => $requireHeaders,
            "onCallBefore" => $onCallBefore,
            "onCallAfter" => $onCallAfter,
            "onCallFinish" => $onCallFinish
        ]);
    }

    /**
     * @param string $method
     * @param string $route
     * @param array $settings
     */
    protected static function setRoute(string $method, string $route, array $settings): void
    {
        preg_match_all("/{(.*?)}/", $route, $matches);
        if (count($matches[0]) > 0) {
            self::$dynamicRoutes[$method][$route] = $settings;
        } else {
            self::$routes[$method][$route] = $settings;
        }
    }

    /**
     * @param string $route
     * @param array $settings
     */
    public static function get(string $route, array $settings): void
    {
        self::other(Response::GET, $route, $settings);
    }

    /**
     * @param string $route
     * @param array $settings
     */
    public static function post(string $route, array $settings): void
    {
        self::other(Response::POST, $route, $settings);
    }

    /**
     * @param string $route
     * @param array $settings
     */
    public static function put(string $route, array $settings): void
    {
        self::other(Response::PUT, $route, $settings);
    }

    /**
     * @param string $route
     * @param array $settings
     */
    public static function delete(string $route, array $settings): void
    {
        self::other(Response::DELETE, $route, $settings);
    }

    /**
     * @param string $route
     * @param array $settings
     */
    public static function patch(string $route, array $settings): void
    {
        self::other(Response::PATCH, $route, $settings);
    }

    /**
     * @param string $route
     * @param array $settings
     */
    public static function options(string $route, array $settings): void
    {
        self::other(Response::OPTIONS, $route, $settings);
    }

    /**
     * @param string $route
     * @param array $settings
     */
    public static function head(string $route, array $settings): void
    {
        self::other(Response::HEAD, $route, $settings);
    }

    /**
     * @param string $type Requisition type. example POST, GET, PUT
     * @param string $base Route base
     * @param array $routes Route configuration.
     * @param array $settingsGroup Group configuration. Note: These settings override the individual ones.
     */
    public static function group(string $type, string $base, array $routes, array $settingsGroup = []): void
    {
        $base = empty($base) ? DIRECTORY_SEPARATOR : $base;

        foreach ($routes as $route => $settings) {
            if (empty($route)) {
                $route = $base;
            } else {
                $route = $base . DIRECTORY_SEPARATOR . $route;
            }

            $route = str_replace("//", DIRECTORY_SEPARATOR, $route);

            if (!empty($settingsGroup)) {
                if (array_key_exists("controller", $settingsGroup)) {
                    $settings["controller"] = $settingsGroup["controller"];
                }

                if (array_key_exists("method", $settingsGroup)) {
                    $settings["method"] = $settingsGroup["method"];
                }

                if (array_key_exists("headers", $settingsGroup)) {
                    $settings["headers"] = $settingsGroup["headers"];
                }

                if (array_key_exists("requireHeaders", $settingsGroup)) {
                    $settings["requireHeaders"] = $settingsGroup["requireHeaders"];
                }

                if (array_key_exists("onCallBefore", $settingsGroup)) {
                    $settings["onCallBefore"] = $settingsGroup["onCallBefore"];
                }

                if (array_key_exists("onCallAfter", $settingsGroup)) {
                    $settings["onCallAfter"] = $settingsGroup["onCallAfter"];
                }

                if (array_key_exists("onCallFinish", $settingsGroup)) {
                    $settings["onCallFinish"] = $settingsGroup["onCallFinish"];
                }
            }

            self::other($type, $route, $settings);
        }
    }

    /**
     * @param string $route
     * @param string $method
     * @return array|null
     */
    public static function getRoute(string $route, string $method = Response::ALL): ?array
    {
        return self::$routes[$method][$route] ?? null;
    }

    /**
     * @param string $route
     * @param string $method
     * @return bool
     */
    public static function verifyRoute(string $route, string $method): bool
    {
        if (isset(self::$routes[$method][$route])) {
            return true;
        } elseif (isset(self::$dynamicRoutes[$method])) {
            foreach (self::$dynamicRoutes[$method] as $index => $settings) {
                preg_match_all("/{(.*?)}/", $index, $variables);
                $key = str_replace($variables[0], '([^/]+)', $index);

                if (preg_match('#^' . $key . '$#', $route, $matches)) {
                    $data = [];

                    foreach ($variables[1] as $k => $variable) {
                        $data[$variable] = $matches[$k + 1];
                    }

                    $settings['parameters'] = $data;
                    self::$routes[$method][$matches[0]] = $settings;

                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param array $route
     * @throws Exception
     */
    public static function validateRoute(array $route): void
    {
        if (isset($route['headers']) && is_array($route['headers'])) {
            foreach ($route['headers'] as $key => $value) {
                Response::getInstance()->setHeader($key, $value);
            }
        }
        if (isset($route['requireHeaders']) && is_array($route['requireHeaders'])) {
            foreach ($route['requireHeaders'] as $key => $value) {
                if ($key === "Content-Type") {
                    $accept = explode(',', Request::getInstance()->getHeader('Accept'));
                    if (!in_array($value, $accept)) {
                        HooksRoutes::getInstance()->onCallError("No have Content-Type in request header");
                    }
                } elseif (Request::getInstance()->getHeader($key) !== $value) {
                    HooksRoutes::getInstance()->onCallError("No have $key in request header");
                }
            }
        }
    }

    public static function clearRoutes(): void
    {
        self::$dynamicRoutes = [];
        self::$routes = [];
    }
}