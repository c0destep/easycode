<?php

namespace System\Libraries;

use Exception;
use Smarty as SmartyLib;
use System\FastApp;

class Smarty extends SmartyLib
{
    private static Smarty $instance;

    /**
     * Smarty constructor.
     */
    private function __construct()
    {
        parent::__construct();

        $this->template_dir = FastApp::getInstance()->getViewPath();
        $this->compile_dir = FastApp::getInstance()->getCachePath() . '/template';

        if (!is_writable($this->compile_dir)) {
            chmod($this->compile_dir, 0755);
        }
    }

    public static function getInstance(): Smarty
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Load View
     * @param string $template
     * @param array $data
     * @param bool $return
     * @return void
     * @throws Exception
     */
    function view(string $template, array $data = [], bool $return = false): void
    {
        if (FastApp::environment('APP_ENV') === 'production') {
            $this->error_reporting = false;
            $this->error_unassigned = false;
        } else {
            $this->error_reporting = true;
            $this->error_unassigned = true;
        }

        $this->assign("FastApp", FastApp::getInstance());

        if (!empty($data)) {
            foreach ($data as $key => $variable) {
                $this->assign($key, $variable);
            }
        }

        try {
            echo $this->fetch($template);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}