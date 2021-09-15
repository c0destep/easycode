<?php

use System\Database\EloquentDriver;
use System\ResponseType;

$Config = array();

$Config['name_project'] = "CodeInsight";

$Config['base_dir'] = "/";
$Config['base_url'] = "http://localhost/";
$Config['https_enable'] = false;
$Config['ssl_verify'] = false; //redir ssl

/**
 * Load Files Route
 * Put the name of file on folder App/Config/Routes
 */
$Config["files_route"] = ["Default"];

/**
 * Config Route
 */
$Config['default_route'] = "Home";

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
    "isActive" => false,
    "class" => EloquentDriver::class, //Class Driver for Database Connection Setup
    "config" => [
        "db_hostname" => "localhost",
        "db_database" => "",
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
$Config['base_dir_assets'] = "public/";

/**
 * Upload Directory
 */
$Config['upload']['image'] = "/public/uploads/img/";
$Config['upload']['docs'] = "/public/uploads/docs/";
$Config['cache_image'] = "/public/cache/img/";

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
 * Autoloads Helpers
 * Checks if the file exists and includes it before starting the controller
 * Ex: Session, Pagination, Text, Upload
 */
$Config['helpersLoad'] = ["Session", "Text", "Upload"];

/**
 * Emails
 */
$Config['Email']["smtp_host"] = "";
$Config['Email']["smtp_user"] = "";
$Config['Email']["smtp_pass"] = "";
$Config['Email']["smtp_port"] = "";
$Config['Email']["smtp_name"] = "";

