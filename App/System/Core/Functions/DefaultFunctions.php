<?php

use JetBrains\PhpStorm\ArrayShape;
use System\Core\DefaultErrors;

if (!function_exists('handler_exception')) {
    /**
     * Handler Error
     * @param $errorNo
     * @param $errorStr
     * @param $errorFile
     * @param $errorLine
     */

    function handler_error($errorNo, $errorStr, $errorFile, $errorLine): void
    {
        DefaultErrors::getInstance()->handlerError($errorNo, $errorStr, $errorFile, $errorLine);
    }
}

if (!function_exists('handler_exception')) {
    /**
     * Handler Error
     * @param $exception
     */
    function handler_exception($exception): void
    {
        DefaultErrors::getInstance()->ErrorXXX($exception->getCode(), $exception);
    }
}

if (!function_exists('shutdownHandler')) {
    /**
     * Handler Parse Error
     */
    function shutdownHandler(): void
    {
        $lastError = error_get_last();

        if (isset($lastError['type']))
            switch ($lastError['type']) {
                case E_ERROR:
                case E_CORE_ERROR:
                case E_COMPILE_ERROR:
                case E_USER_ERROR:
                case E_RECOVERABLE_ERROR:
                case E_CORE_WARNING:
                case E_COMPILE_WARNING:
                case E_PARSE:
                    handler_error($lastError['type'], $lastError['message'], $lastError['file'], $lastError['line']);
            }
    }
}

if (!function_exists('loaderFastApp')) {
    /**
     * Autoload Class
     * @param string $class
     * @return void
     */
    function loaderFastApp(string $class): void
    {
        $filename = BASE_PATH . DIRECTORY_SEPARATOR . str_replace('\\', '/', $class) . '.php';
        $filename = str_replace("//", "/", $filename);

        if (file_exists($filename)) {
            require_once($filename);
        } else {
            $filename = BASE_PATH_THIRD . DIRECTORY_SEPARATOR . str_replace('\\', '/', $class) . '.php';
            if (file_exists($filename)) require_once($filename);
        }
    }
}

if (!function_exists('getJsonPost')) {
    /**
     * Get JSON data sent in the Body of a request
     * @return mixed
     */
    function getJsonPost(): mixed
    {
        return json_decode(file_get_contents('php://input'), true);
    }
}

if (!function_exists('getallheaders')) {
    /**
     * Obter todos os cabeçalhos passados na requisição
     * @return array
     */
    function getallheaders()
    {
        $headers = array();
        $copy_server = array(
            'CONTENT_TYPE' => 'Content-Type',
            'CONTENT_LENGTH' => 'Content-Length',
            'CONTENT_MD5' => 'Content-Md5',
        );
        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) === 'HTTP_') {
                $key = substr($key, 5);
                if (!isset($copy_server[$key]) || !isset($_SERVER[$key])) {
                    $key = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', $key))));
                    $headers[$key] = $value;
                }
            } elseif (isset($copy_server[$key])) {
                $headers[$copy_server[$key]] = $value;
            }
        }
        if (!isset($headers['Authorization'])) {
            if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                $headers['Authorization'] = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
            } elseif (isset($_SERVER['PHP_AUTH_USER'])) {
                $basic_pass = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';
                $headers['Authorization'] = 'Basic ' . base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $basic_pass);
            } elseif (isset($_SERVER['PHP_AUTH_DIGEST'])) {
                $headers['Authorization'] = $_SERVER['PHP_AUTH_DIGEST'];
            }
        }
        return $headers;
    }
}

if (!function_exists("getViewPhp")) {
    /**
     * Get HTML template PHP
     * @param string $file
     * @param array $data
     * @return void
     */
    function getViewPhp(string $file, array $data = array()): void
    {
        extract($data);
        include BASE_PATH . "Views/" . $file;
    }
}

if (!function_exists("getClientIpServer")) {
    /**
     * @return mixed
     */
    function getClientIpServer(): mixed
    {
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipAddress = $_SERVER['HTTP_X_FORWARDED'];
        } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipAddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
            $ipAddress = $_SERVER['HTTP_FORWARDED'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ipAddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipAddress = 'UNKNOWN, BUT IT MAY BE FROM THE FUTURE';
        }

        return $ipAddress;
    }
}

if (!function_exists("detectBrowser")) {
    /**
     * @param string|null $userAgent
     * @param string|null $ip
     * @return array
     */
    #[ArrayShape(['ip' => "mixed|null|string", 'userAgent' => "mixed|null|string", 'name' => "string", 'platform' => "string", 'pattern' => "string", 'version' => "mixed"])] function detectBrowser(string $userAgent = null, string $ip = null): array
    {
        if (is_null($ip)) $ip = getClientIpServer();
        if (is_null($userAgent)) $userAgent = $_SERVER['HTTP_USER_AGENT'];

        $browser = 'Unknown';
        $codename = 'Unknown';
        $platform = 'Unknown';

        if (preg_match('/linux/i', $userAgent)) {
            $platform = 'Linux';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            $platform = 'MacOS';
        } elseif (preg_match('/windows|win32/i', $userAgent)) {
            $platform = 'Windows';
        }

        if (preg_match('/Edge/i', $userAgent)) {
            $browser = 'Microsoft Edge';
            $codename = 'Edge';
        } elseif (preg_match('/MSIE/i', $userAgent) && !preg_match('/Opera/i', $userAgent)) {
            $browser = 'Internet Explorer';
            $codename = 'MSIE';
        } elseif (preg_match('/Trident/i', $userAgent) && !preg_match('/Opera/i', $userAgent)) {
            $browser = 'Internet Explorer';
            $codename = 'Trident';
        } elseif (preg_match('/Firefox/i', $userAgent)) {
            $browser = 'Mozilla Firefox';
            $codename = 'Firefox';
        } elseif (preg_match('/Chrome/i', $userAgent)) {
            $browser = 'Google Chrome';
            $codename = 'Chrome';
        } elseif (preg_match('/AppleWebKit/i', $userAgent)) {
            $browser = 'AppleWebKit';
            $codename = 'Opera';
        } elseif (preg_match('/Safari/i', $userAgent)) {
            $browser = 'Apple Safari';
            $codename = 'Safari';
        } elseif (preg_match('/Netscape/i', $userAgent)) {
            $browser = 'Netscape';
            $codename = 'Netscape';
        }

        $known = array('Version', $codename, 'other');
        $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        !preg_match_all($pattern, $userAgent, $matches);
        $i = count($matches['browser']);

        if ($i != 1) {
            if (strripos($userAgent, "Version") < strripos($userAgent, $codename)) {
                $version = $matches['version'][0];
            } else {
                $version = $matches['version'][1];
            }
        } else {
            $version = $matches['version'][0];
        }

        if ($codename == "Trident") {
            preg_match('#rv:([0-9.|a-zA-Z.]*)#', $userAgent, $versions);
            $version = $versions[1];
        }

        return array(
            'ip' => $ip,
            'userAgent' => $userAgent,
            'name' => $browser,
            'platform' => $platform,
            'pattern' => $pattern,
            'version' => $version
        );
    }
}
