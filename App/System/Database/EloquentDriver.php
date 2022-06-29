<?php

namespace System\Database;

use Illuminate\Database\Capsule\Manager as Capsule;

class EloquentDriver implements DriverImplements
{

    public function createConnection()
    {
        $capsule = new Capsule();
        $capsule->addConnection([
            'driver' => $_ENV['DB_DRIVER'],
            'host' => $_ENV['DB_HOST'],
            'database' => $_ENV['DB_NAME'],
            'username' => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASS'],
            'charset' => $_ENV['DB_CHARSET'] ?? 'utf8',
            'collation' => $_ENV['DB_COLLATION'] ?? 'utf8_unicode_ci',
            'prefix' => $_ENV['DB_PREFIX'] ?? '',
        ]);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }

}
