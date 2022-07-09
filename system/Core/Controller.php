<?php

namespace System\Core;

use Exception;
use System\Libraries\Smarty;

class Controller
{
    /**
     * Controller builder
     * Controller constructor.
     */
    public function __construct()
    {
    }

    /**
     * Set Content-Type of header
     * @param $type string Type of Response
     */
    /*public function setResponseType(string $type): void
    {
        Response::getInstance()->setContentType($type);
    }*/

    /**
     * Load view Smarty
     * @param string $file String with the name of the view
     * @param array $data Associative array with parameters for the view
     * @param bool $return True to return HTML
     * @return void
     * @throws Exception
     */
    public function setView(string $file, array $data = [], bool $return = false): void
    {
        Smarty::getInstance()->view($file . '.tpl', $data, $return);
    }
}