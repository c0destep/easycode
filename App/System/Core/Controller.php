<?php

namespace System\Core;

use Exception;
use System\FastApp;
use System\Libraries\HtmlBlocks;
use System\Libraries\Session as Session;
use System\Libraries\Smarty as Smarty;
use System\Response;

class Controller
{
    private mixed $hasEngine;
    private ?Smarty $smarty = null;

    /**
     * Controller builder
     * Controller constructor.
     */
    public function __construct()
    {
        $this->hasEngine = FastApp::getInstance()->getConfig("template");
        if ($this->hasEngine === TEMPLATE_ENGINE_SMARTY) {
            $this->smarty = Smarty::getInstance();
        }
    }

    /**
     * Get the Framework instance
     * @return null|FastApp Get app instance
     */
    public function getApp(): ?FastApp
    {
        return FastApp::getInstance();
    }

    /**
     * Get Session
     * @return null|Session get session instance system Libraries
     */
    public function getSession(): ?Session
    {
        return Session::getInstance();
    }

    /**
     * Set Content-Type of header
     * @param $type string Type of Response
     */
    public function setResponseType(string $type): void
    {
        Response::getInstance()->setContentType($type);
    }

    /**
     * Add a Helper
     * @param string $file Helper file name
     * @throws Exception
     */
    public function loadHelper(string $file): void
    {
        FastApp::getInstance()->loadHelper($file);
    }

    /**
     * Load view Smarty
     * @param string $file String with the name of the view
     * @param array $data Associative array with parameters for the view
     * @param bool $return True to return HTML
     * @return null|string
     */
    public function setView(string $file, array $data = array(), bool $return = false): ?string
    {
        if ($this->hasEngine === TEMPLATE_ENGINE_SMARTY) {
            return $this->smarty->view($file . ".tpl", $data, $return);
        } else {
            return $this->setViewWithoutTemplate($file, $data, $return);
        }
    }

    /**
     * Default view without template
     * @param $_file_
     * @param array $data Associative array with parameters for the view
     * @param bool $return True to return HTML
     * @return null|string
     */
    public function setViewWithoutTemplate($_file_, array $data = array(), bool $return = false): ?string
    {
        extract($data);
        if ($return) {
            HtmlBlocks::getInstance()->initBlock();
            include BASE_PATH . "Views/" . $_file_ . ".php";
            HtmlBlocks::getInstance()->endBlock("GetTemplate");
            return HtmlBlocks::getInstance()->getBlocks("GetTemplate", 0);
        } else {
            include BASE_PATH . "Views/" . $_file_ . ".php";
            return null;
        }
    }
}