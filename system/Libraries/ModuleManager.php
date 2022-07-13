<?php

namespace System\Libraries;

use System\FastApp;

class ModuleManager
{
    protected static array $modules = [];

    public function __construct()
    {
        $this->setup();
    }

    public function setup(): void
    {
        $GetSettings = json_decode(file_get_contents(FastApp::getInstance()->getModulePath() . "/settings.json"));
        $RoutesFile = [];
        foreach ($GetSettings as $modules) {
            if ($modules->active) {
                if (file_exists(FastApp::getInstance()->getModulePath() . sprintf("/%s/settings.php", $modules->key))) {
                    require_once FastApp::getInstance()->getModulePath() . sprintf("/%s/settings.php", $modules->key);
                }

                if (file_exists(FastApp::getInstance()->getModulePath() . sprintf("/%s/routes/web.php", $modules->key))) {
                    $RoutesFile[] = FastApp::getInstance()->getModulePath() . sprintf("/%s/routes/web.php", $modules->key);
                } else if (file_exists(FastApp::getInstance()->getModulePath() . sprintf("/%s/routes/api.php", $modules->key))) {
                    $RoutesFile[] = FastApp::getInstance()->getModulePath() . sprintf("/%s/routes/api.php", $modules->key);
                }
            }
        }

        self::$modules = $GetSettings;
    }

    public static function getModules(): array
    {
        return self::$modules;
    }
}