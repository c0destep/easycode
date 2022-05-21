<?php

namespace System\Core;

use System\Request;
use System\Response;

class Routes
{
    protected static array $Routes = array();
    protected static array $DynamicRoutes = array();

    /**
     * @param string $type Type Request
     * @param string $route route url
     * @param array $class
     * @param array $Headers Response Headers
     * @param array $RequireHeader Require headers on request
     * @param array $onCallBefore Call on Before method controller
     * @param array $onCallAfter Call on After method controller
     * @param array $onCallFinish Call on Finish controller
     */
    public static function simple(string $type, string $route, array $class, array $Headers = array(), array $RequireHeader = array(), array $onCallBefore = array(), array $onCallAfter = array(), array $onCallFinish = array()): void
    {
        self::setRoute($type, $route, [
            "Controller" => $class[0],
            "Method" => $class[1],
            "Headers" => $Headers,
            "RequireHeader" => $RequireHeader,
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
            self::$DynamicRoutes[$method][$route] = $settings;
            return;
        }
        self::$Routes[$method][$route] = $settings;
    }

    /**
     * @param string $route
     * @param array $settings
     */
    public static function all(string $route, array $settings): void
    {
        self::setRoute(Response::ALL, $route, $settings);
    }

    /**
     * @param string $route
     * @param array $settings
     */
    public static function get(string $route, array $settings): void
    {
        self::setRoute(Response::GET, $route, $settings);
    }

    /**
     * @param string $route
     * @param array $settings
     */
    public static function post(string $route, array $settings): void
    {
        self::setRoute(Response::POST, $route, $settings);
    }

    /**
     * @param string $route
     * @param array $settings
     */
    public static function put(string $route, array $settings): void
    {
        self::setRoute(Response::PUT, $route, $settings);
    }

    /**
     * @param string $route
     * @param array $settings
     */
    public static function delete(string $route, array $settings): void
    {
        self::setRoute(Response::DELETE, $route, $settings);
    }

    /**
     * @param string $route
     * @param array $settings
     */
    public static function patch(string $route, array $settings): void
    {
        self::setRoute(Response::PATCH, $route, $settings);
    }

    /**
     * @param string $route
     * @param array $settings
     */
    public static function options(string $route, array $settings): void
    {
        self::setRoute(Response::OPTIONS, $route, $settings);
    }

    /**
     * @param string $route
     * @param array $settings
     */
    public static function head(string $route, array $settings): void
    {
        self::setRoute(Response::HEAD, $route, $settings);
    }

    /**
     * @param string $type
     * @param string $base
     * @param array $controllers
     * @param array $settings
     */
    public static function group(string $type, string $base, array $controllers, array $settings = []): void
    {
        foreach ($controllers as $route => $controller) {
            $settings = array_merge($settings, $controller);

            if (!empty($route)) {
                $route = "$base/$route";
            } else {
                $route = $base;
            }

            $finalRoute = str_replace("//", "/", $route);

            self::other($type, $finalRoute, $settings);
        }
    }

    /**
     * @param string $type
     * @param string $route
     * @param array $settings
     */
    public static function other(string $type, string $route, array $settings): void
    {
        self::setRoute($type, $route, $settings);
    }

    /**
     * @param string $route
     * @param string $method
     * @return mixed
     */
    public static function getRoute(string $route, string $method): mixed
    {
        if (isset(self::$Routes[$method][$route])) {
            return self::$Routes[$method][$route];
        } elseif (isset(self::$Routes[Response::ALL][$route])) {
            return self::$Routes[Response::ALL][$route];
        } else {
            return null;
        }
    }

    /**
     * @param string $route
     * @param string $method
     * @return mixed
     */
    public static function verifyRoute(string $route, string $method): mixed
    {
        if (isset(self::$Routes[$method][$route])) {
            return self::$Routes[$method][$route];
        } elseif (isset(self::$Routes[Response::ALL][$route])) {
            return self::$Routes[Response::ALL][$route];
        } elseif (isset(self::$DynamicRoutes[$method])) {
            foreach (self::$DynamicRoutes[$method] as $type => $args) {
                preg_match_all("/{(.*?)}/", $type, $vars);
                $key = str_replace($vars[0], '([^/]+)', $type);
                if (preg_match('#^' . $key . '$#', $route, $matches)) {
                    $attrs = [];

                    foreach ($vars[1] as $k => $var) {
                        $attrs[$var] = $matches[$k + 1];
                    }

                    $args['Attrs'] = $attrs;
                    self::$Routes[$method][$matches[0]] = $args;

                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param array $route
     */
    public static function validateRoute(array $route): void
    {
        if (isset($route['Headers']) && is_array($route['Headers'])) {
            foreach ($route['Headers'] as $header) {
                Response::getInstance()->setHeader($header);
            }
        }
        if (isset($route['RequireHeader']) && is_array($route['RequireHeader'])) {
            foreach ($route['RequireHeader'] as $key => $header) {
                if (Request::getInstance()->getHeader($key) !== $header) {
                    HooksRoutes::getInstance()->onCallError("No have \"$key\" in request header");
                }
            }
        }
    }

    public static function clearRoutes(): void
    {
        self::$DynamicRoutes = array();
        self::$Routes = array();
    }
}