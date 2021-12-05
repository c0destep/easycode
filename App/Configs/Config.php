<?php

use System\Database\EloquentDriver;
use System\ResponseType;

$Config = array();

$Config['name_project'] = "Easycode";

$Config['base_dir'] = "/Codingstep/easycode/";
$Config['route'] = "http://localhost/Codingstep/easycode/";
$Config['https_enable'] = false;
$Config['ssl_verify'] = false;

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
$Config['encrypt_key'] = "default";

/**
 * DATABASE CONFIG
 */
$Config['db_driver'] = [
    "isActive" => true,
    "class" => EloquentDriver::class, //Class Driver for Database Connection Setup
    "config" => [
        "db_hostname" => "localhost",
        "db_database" => "codingstep",
        "db_username" => "root",
        "db_password" => "",
    ]
];

/**
 * SESSION CONFIG
 */
$Config['session_id'] = "sphap"; // Name Session


/**
 * Default assets dir
 */
$Config['base_dir_assets'] = "Public/";

/**
 * Upload Directory
 */
$Config['upload']['image'] = "/Public/uploads/image/";
$Config['upload']['docs'] = "/Public/uploads/documents/";
$Config['cache_image'] = "/Public/cache/image/";

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

