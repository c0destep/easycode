<?php
namespace Controller;


use System\Core\Controller;

class Index extends Controller {

    public function __construct(){
        parent::__construct();
    }

    public function Index(){
        $this->view("Layout/Content",[
            "layout" => "welcome.tpl"
        ]);
    }

}