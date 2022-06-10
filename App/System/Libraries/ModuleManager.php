<?php

namespace System\Libraries;

class ModuleManager
{
    protected static mixed $modules;

    public static function getModules(): mixed
    {
        return self::$modules;
    }

    public function setup(): void
    {
        $GetSettings = json_decode(file_get_contents(BASE_PATH . "Modules/Settings.json"));
        $RoutesFile = getConfig("files_route");
        foreach ($GetSettings as $modules) {
            if ($modules->active) {
                if (file_exists(BASE_PATH . sprintf("Modules/%s/Settings.php", $modules->key)))
                    require_once BASE_PATH . sprintf("Modules/%s/Settings.php", $modules->key);

                if (file_exists(BASE_PATH . sprintf("Modules/%s/Routes.php", $modules->key)))
                    $RoutesFile[] = BASE_PATH . sprintf("Modules/%s/Routes.php", $modules->key);
            }
        }

        setConfig("files_route", $RoutesFile);
        self::$modules = $GetSettings;
    }
}