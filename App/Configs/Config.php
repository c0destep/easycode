<?php

use System\Database\EloquentDriver;
use System\ResponseType;

$Config = array();

$Config['name_project'] = "Easycode";

$dir_len = strlen('App/Configs');
$B = substr(__FILE__, 0, strrpos(__FILE__, DIRECTORY_SEPARATOR));
$A = substr($_SERVER['DOCUMENT_ROOT'], strrpos($_SERVER['DOCUMENT_ROOT'], $_SERVER['PHP_SELF']));
$C = substr($B, strlen($A));
$pos_config = strlen($C) - $dir_len - 1;
$D = substr($C, 0, $pos_config);
$protocol = (isset($_SERVER['HTTPS']) && filter_var($_SERVER['HTTPS'], FILTER_VALIDATE_BOOLEAN)) ? 'https://' : 'http://';
$Config['base_dir'] = $D;
$Config['route'] = $protocol . $_SERVER['SERVER_NAME'] . $D;

/**
 * Load Files Route
 * Put the name of file on folder App/Config/Routes
 */
$Config["files_route"] = ["Default"];

/**
 * Config Route
 */
$Config['default_route'] = "/";

/**
 *
 */
$Config['error_content_type'] = ResponseType::CONTENT_HTML;
$Config['error_extra_headers'] = [];

$Config['enable_query_strings'] = true;
$Config['encrypt_key'] = "JktUSnhrOENjWnhjVF1kLA==";

/**
 * DATABASE CONFIG
 */
$Config['db_driver'] = [
    "isActive" => true,
    "class" => EloquentDriver::class, //Class Driver for Database Connection Setup
    "config" => [
        "db_hostname" => "localhost",
        "db_database" => "easycode",
        "db_username" => "root",
        "db_password" => "",
    ]
];

/**
 * SESSION CONFIG
 */
$Config['session_id'] = uniqid(strtolower($Config['name_project'] . '_'));


/**
 * Default assets dir
 */
$Config['base_dir_assets'] = "public/";

/**
 * Upload Directory
 */
$Config['upload']['image'] = "/public/uploads/images/";
$Config['upload']['video'] = "/public/uploads/videos/";
$Config['upload']['document'] = "/public/uploads/documents/";
$Config['cache_image'] = "/public/cache/images/";

/**
 * Default Lang
 */
$Config['lang'] = "pt-br";

/**
 * Template Engine
 * TEMPLATE_ENGINE_SMARTY Use Smarty Template
 * TEMPLATE_WITHOUT_ENGINE Use direct PHP file
 *
 */
$Config['template'] = TEMPLATE_ENGINE_SMARTY;

/**
 * Timezone
 */
$Config['timezone'] = "America/Sao_Paulo";

/**
 * Autoload Helpers
 * Checks if the file exists and includes it before starting the controller
 * Ex: Session, Pagination, Text, Upload
 */
$Config['helpersLoad'] = ["Session", "Text", "Upload", "Utils"];

/**
 * Emails
 */
$Config['Email']["smtp_host"] = "";
$Config['Email']["smtp_user"] = "";
$Config['Email']["smtp_pass"] = "";
$Config['Email']["smtp_port"] = "";
$Config['Email']["smtp_name"] = "";