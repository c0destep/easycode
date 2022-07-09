<?php

namespace System;

use Dotenv\Dotenv;
use Exception;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use System\Core\HooksRoutes;
use System\Core\Routes;
use System\Database\EloquentDriver;

class FastApp
{
    const VERSION = 'v0.5.7-dev';
    private static FastApp $instance;
    private static array $environments;
    protected string $rootPath;
    protected string $basePath;
    protected string $cachePath;
    protected string $supportPath;
    protected string $modelPath;
    protected string $viewPath;
    protected string $modulePath;
    protected string $routePath;
    protected string $baseRoute;
    protected string $requestURI;
    protected string $requestMethod;
    protected array $route = [];
    protected array $parameters = [];
    protected array $patch = [];
    protected array $supports = [];

    /**
     * FastApp constructor.
     * @throws Exception
     */
    private function __construct()
    {
        self::$instance = $this;

        $this->setRootPath(dirname(__DIR__));
        $this->setBasePath($this->getRootPath() . '/app');
        $this->setCachePath($this->getRootPath() . '/cache');
        $this->setSupportPath($this->getRootPath() . '/system/Helpers');
        $this->setModelPath($this->getRootPath() . '/models');
        $this->setViewPath($this->getRootPath() . '/views');
        $this->setModulePath($this->getRootPath() . '/modules');
        $this->setRoutePath($this->getRootPath() . '/routes');

        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        $dotenv->required('APP_ENV')->allowedValues(['development', 'production']);

        $dotenv->required('DEFAULT_LANGUAGE')->notEmpty();
        $dotenv->ifPresent('AVAILABLE_LANGUAGES')->notEmpty();

        $dotenv->required('DB_ACTIVE')->isBoolean();
        $dotenv->ifPresent('DB_DRIVER')->notEmpty();
        $dotenv->ifPresent('DB_HOST')->notEmpty();
        $dotenv->ifPresent('DB_NAME')->notEmpty();
        $dotenv->ifPresent('DB_USER')->notEmpty();

        self::$environments = $_ENV;

        switch (self::environment('APP_ENV')) {
            case 'development':
                ini_set('display_errors', true);
                error_reporting(-1);
                break;
            case 'production':
                ini_set('display_errors', false);
                error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
                break;
            default:
                header('HTTP/1.1 503 Service Unavailable.', true, 503);
                echo 'The application environment is not set correctly.';
                exit(503);
        }

        $pathDirectoryServer = substr($_SERVER['DOCUMENT_ROOT'], strrpos($_SERVER['DOCUMENT_ROOT'], $_SERVER['PHP_SELF']));
        $pathDirectorySystem = substr($this->getRootPath() . '/public/', strlen($pathDirectoryServer));
        $directoryApp = substr($pathDirectorySystem, 0, strlen($pathDirectorySystem));
        $protocol = (isset($_SERVER['HTTPS']) && filter_var($_SERVER['HTTPS'], FILTER_VALIDATE_BOOLEAN)) ? 'https://' : 'http://';

        $this->setBaseRoute($protocol . $_SERVER['SERVER_NAME'] . $directoryApp);

        $this->setSupports(['System', 'Session', 'Upload']);
        $this->loadSupports();
        $this->loadHelpers();
        $this->loadRoutes();

        /*$Modulo = new ModuleManager();
        $Modulo->setup();*/

        //Hooks::executeCallBefore();

        if (self::environment('DB_ACTIVE')) {
            $eloquent = new EloquentDriver();
            $eloquent->createConnection();
        }

        $this->setRequestURI(str_replace([$_SERVER['QUERY_STRING'], "?", $directoryApp], "", getenv("REQUEST_URI")));
        $this->setRequestMethod(getenv('REQUEST_METHOD'));
        $this->setPatch(explode(DIRECTORY_SEPARATOR, $this->getRequestURI()));

        if (empty($this->getRequestURI())) {
            $this->setRequestURI(DIRECTORY_SEPARATOR);
            $this->setPatch([$this->getRequestURI()]);
        }

        if (!Routes::verifyRoute($this->getRequestURI(), $this->getRequestMethod())) {
            $nController = "App\\Controller\\" . $this->getPatch()[0];
            $nMethod = $this->getPatch()[1];

            if (!execute_class($nController, $nMethod)) {
                HooksRoutes::getInstance()->onNotFound();
            }
        } else {
            $this->setRoute(Routes::getRoute($this->getRequestURI(), $this->getRequestMethod()));

            Routes::validateRoute($this->getRoute());
            Routes::clearRoutes();

            execute_callbacks($this->getRoute(), 'onCallBefore', $this->getRouteSetting('parameters'));
            if (execute_class($this->getRouteSetting('controller'), $this->getRouteSetting('method'), $this->getRouteSetting('parameters'))) {
                execute_callbacks($this->getRoute(), 'onCallAfter', $this->getRouteSetting('parameters'));
            } else {
                HooksRoutes::getInstance()->onNotFound();
            }
        }

        //Hooks::executeCallAfter();
    }

    /**
     * @return string
     */
    public function getRootPath(): string
    {
        return $this->rootPath;
    }

    /**
     * @param string $rootPath
     * @return void
     */
    public function setRootPath(string $rootPath): void
    {
        $this->rootPath = rtrim($rootPath);
    }

    /**
     * @throws Exception
     */
    public static function environment(string|array $env): string|array
    {
        if (is_array($env)) {
            $temp = [];

            foreach ($env as $key) {
                if (array_key_exists($key, self::$environments)) {
                    $temp[] = self::$environments[$key];
                } else {
                    $temp[] = null;
                }
            }

            return $temp;
        }

        if (array_key_exists($env, self::$environments)) {
            return self::$environments[$env];
        } else {
            throw new Exception("$env not found");
        }
    }

    /**
     * @throws Exception
     */
    private function loadSupports(): void
    {
        foreach ($this->getSupports() as $nameFile) {
            if (file_exists($this->supportPath . DIRECTORY_SEPARATOR . $nameFile . '.php')) {
                require $this->supportPath . DIRECTORY_SEPARATOR . $nameFile . '.php';
            } else {
                throw new Exception("Support file $nameFile not found");
            }
        }
    }

    /**
     * @return array|string[]
     */
    public function getSupports(): array
    {
        return $this->supports;
    }

    /**
     * @param string|array|string[] $supports
     * @return void
     */
    public function setSupports(string|array $supports): void
    {
        if (is_array($supports)) {
            $this->supports = array_merge($this->supports, $supports);
        }

        if (is_string($supports)) {
            $this->supports[] = $supports;
        }
    }

    private function loadHelpers(): void
    {
        $directory = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->supportPath, FilesystemIterator::SKIP_DOTS));

        foreach ($directory as $filePath => $fileInfo) {
            if (pathinfo($filePath)['extension'] === 'php') {
                require($filePath);
            }
        }
    }

    private function loadRoutes(): void
    {
        $directory = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->routePath, FilesystemIterator::SKIP_DOTS));

        foreach ($directory as $filePath => $fileInfo) {
            if (pathinfo($filePath)['extension'] === 'php') {
                require($filePath);
            }
        }
    }

    /**
     * @return string
     */
    public function getRequestURI(): string
    {
        return $this->requestURI;
    }

    /**
     * @param string $requestURI
     * @return void
     */
    public function setRequestURI(string $requestURI): void
    {
        $this->requestURI = $requestURI;
    }

    /**
     * @return string
     */
    public function getRequestMethod(): string
    {
        return $this->requestMethod;
    }

    /**
     * @param string $requestMethod
     * @return void
     */
    public function setRequestMethod(string $requestMethod): void
    {
        $this->requestMethod = $requestMethod;
    }

    /**
     * @return array
     */
    public function getPatch(): array
    {
        return $this->patch;
    }

    /**
     * @param array $patch
     * @return void
     */
    public function setPatch(array $patch): void
    {
        $this->patch = $patch;
    }

    /**
     * @return FastApp
     */
    public static function getInstance(): FastApp
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @return array
     */
    public function getRoute(): array
    {
        return $this->route;
    }

    /**
     * @param array $route
     * @return void
     */
    public function setRoute(array $route): void
    {
        $this->route = $route;
    }

    /**
     * @param string $key
     * @return string|array
     * @throws Exception
     */
    public function getRouteSetting(string $key): string|array
    {
        if (!array_key_exists($key, $this->route)) {
            if ($key === 'parameters') {
                return [];
            }

            throw new Exception("$key not found");
        }

        return $this->route[$key];
    }

    /**
     * @return string
     */
    public function getRoutePath(): string
    {
        return $this->routePath;
    }

    /**
     * @param string $routePath
     * @return void
     */
    public function setRoutePath(string $routePath): void
    {
        $this->routePath = $routePath;
    }

    /**
     * @return string
     */
    public function getBaseRoute(): string
    {
        return $this->baseRoute;
    }

    /**
     * @param string $baseRoute
     * @return void
     */
    public function setBaseRoute(string $baseRoute): void
    {
        $this->baseRoute = $baseRoute;
    }

    public function getVersion(): string
    {
        return self::VERSION;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     * @return void
     */
    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    /**
     * @param int $key
     * @return array
     * @throws Exception
     */
    public function getParameter(int $key): mixed
    {
        if (!array_key_exists($key, $this->parameters)) {
            throw new Exception("Parameter $key not found");
        }

        return $this->parameters[$key];
    }

    /**
     * @return string
     */
    public function getBasePath(): string
    {
        return $this->basePath;
    }

    /**
     * @param string $basePath
     * @return void
     */
    public function setBasePath(string $basePath): void
    {
        $this->basePath = rtrim($basePath);
    }

    /**
     * @return string
     */
    public function getModulePath(): string
    {
        return $this->modulePath;
    }

    /**
     * @param string $modulePath
     * @return void
     */
    public function setModulePath(string $modulePath): void
    {
        $this->modulePath = $modulePath;
    }

    /**
     * @return string
     */
    public function getViewPath(): string
    {
        return $this->viewPath;
    }

    /**
     * @param string $viewPath
     * @return void
     */
    public function setViewPath(string $viewPath): void
    {
        $this->viewPath = $viewPath;
    }

    /**
     * @return string
     */
    public function getSupportPath(): string
    {
        return $this->supportPath;
    }

    /**
     * @param string $supportPath
     * @return void
     */
    public function setSupportPath(string $supportPath): void
    {
        $this->supportPath = $supportPath;
    }

    /**
     * @return string
     */
    public function getModelPath(): string
    {
        return $this->modelPath;
    }

    /**
     * @param string $modelPath
     * @return void
     */
    public function setModelPath(string $modelPath): void
    {
        $this->modelPath = $modelPath;
    }

    /**
     * @return string
     */
    public function getCachePath(): string
    {
        return $this->cachePath;
    }

    /**
     * @param string $cachePath
     * @return void
     */
    public function setCachePath(string $cachePath): void
    {
        $this->cachePath = $cachePath;
    }

    /**
     * @throws Exception
     */
    public function __destruct()
    {
        execute_callbacks($this->getRoute(), 'onCallFinish', $this->getRouteSetting('parameters'));
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }
}
