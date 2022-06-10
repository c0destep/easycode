<?php

namespace System;

use Exception;
use System\Core\DefaultErrors;
use System\Core\HooksRoutes;
use System\Core\Routes;
use System\Libraries\Hooks;
use System\Libraries\Lang;
use System\Libraries\ModuleManager;

class FastApp
{
    protected static FastApp $instance;
    protected mixed $Config;
    protected ?array $Patch;
    protected mixed $RequestURI;
    protected mixed $Route;
    protected ?array $Params;

    /**
     * FastApp constructor.
     * @param bool $onlyLoad
     * @throws Exception
     */
    public function __construct(bool $onlyLoad = false)
    {
        self::$instance = $this;

        $this->loadHelper("System");

        if (getConfig('https_enable')) $this->sslRedirect();

        if ($onlyLoad) return;

        Lang::getInstance()->load("System");

        $Modulo = new ModuleManager();
        $Modulo->setup();

        Hooks::executeCallBefore();

        loadFilesRoute();

        try {
            $HelpersLoads = getConfig('helpersLoad');
            if (is_array($HelpersLoads)) {
                foreach ($HelpersLoads as $helper) {
                    $this->loadHelper($helper);
                }
            }
        } catch (Exception $exception) {
            DefaultErrors::getInstance()->ErrorXXX($exception->getCode(), $exception);
        }

        $this->initDatabase();

        $this->RequestURI = getUriPatch();
        $RequestMethod = getenv('REQUEST_METHOD');

        $this->rePatch($this->RequestURI);
        if (empty($this->Patch[0]) && !empty(getConfig("default_route"))) {
            $this->RequestURI = getConfig("default_route");
            $this->rePatch($this->RequestURI);
        }

        if (!Routes::verifyRoute($this->RequestURI, $RequestMethod)) {

            $nController = "\\Controller\\" . $this->Patch[0];
            $nMethod = $this->Patch[1] ?? "index";

            if (!execute_class($nController, $nMethod)) goto OnNotFound;
        } else {
            $this->Route = Routes::getRoute($this->RequestURI, $RequestMethod);

            Routes::validateRoute($this->Route);
            Routes::clearRoutes();

            execute_callbacks($this->Route, 'onCallBefore', $this->Route['Attrs'] ?? []);
            if (execute_class($this->Route['Controller'], $this->Route['Method'], $this->Route['Attrs'] ?? [])) {
                execute_callbacks($this->Route, 'onCallAfter', $this->Route['Attrs'] ?? []);
                return;
            }

            goto OnNotFound;
        }

        Hooks::executeCallAfter();

        OnNotFound:{
        HooksRoutes::getInstance()->onNotFound();
    }
    }

    /**
     * @param string $file Helper file name
     * @return void
     * @throws Exception
     */
    public function loadHelper(string $file): void
    {
        $isFind = false;

        if (file_exists(BASE_PATH . "Helpers/" . $file . ".php")) {
            require(BASE_PATH . "Helpers/" . $file . ".php");
            $isFind = true;
        }
        if (file_exists(BASE_PATH . "System/Helpers/" . $file . ".php")) {
            require(BASE_PATH . "System/Helpers/" . $file . ".php");
            $isFind = true;
        }
        if (!$isFind) {
            throw new Exception("File Helper $file not found");
        }
    }

    /**
     * Redirect HTTPS
     */
    private function sslRedirect(): void
    {
        if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
            $location = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: ' . $location);
            exit;
        }
    }

    /**
     * @return FastApp
     */
    public static function getInstance(): FastApp
    {
        if (is_null(self::$instance)) self::$instance = new FastApp(true);
        return self::$instance;
    }

    /**
     * Starts database settings
     */
    private function initDatabase(): void
    {
        $Config = getConfig("db_driver");
        if ($Config["isActive"] && (!is_null($Config['class']))) {
            if (class_exists($Config['class'])) {
                $DriverClass = $Config['class'];
                $Driver = new $DriverClass();
                $Driver->createConnection($Config["config"]);
            }
        }
    }

    /**
     * Explode nos paths do URL
     * @param string $Folder
     */
    public function rePatch(string $Folder): void
    {
        $this->Patch = explode("/", $Folder);
    }

    /**
     * Obter valor de um path da url
     * @param string $key int Número do indice
     * @return null|string Retorna o valor do indice ou null se não existir
     */
    public function getPatch(string $key): ?string
    {
        return $this->Patch[$key] ?? null;
    }

    /**
     * Obter valores de configuração
     * @param $key String indice da configuração que deseja
     * @return mixed Retorna valor de configuração do indice definido
     */
    public function getConfig(string $key): mixed
    {
        return getConfig($key) ?? null;
    }

    /**
     * Obter a url atual
     * @return mixed
     */
    public function getUri(): mixed
    {
        return $this->RequestURI ?? null;
    }

    /**
     * Verifica se o valor passado é igual o da URL
     * @param $uri String URL de comparação
     * @return bool
     */
    public function isUri(string $uri): bool
    {
        return ($this->RequestURI === $uri);
    }

    /**
     * Destruc, executado no final de tudo
     */
    public function __destruct()
    {
        execute_callbacks($this->Route, 'onCallFinish');
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function addParams(string $key, mixed $value): void
    {
        $this->Params[$key] = $value;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getParam(string $key): mixed
    {
        return $this->Params[$key] ?? null;
    }
}
