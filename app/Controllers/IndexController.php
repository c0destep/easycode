<?php

namespace App\Controllers;

use Easycode\Routing\Controller;
use Easycode\View\ViewHtml;

class IndexController extends Controller
{
    /**
     * @return ViewHtml
     */
    public function index(): ViewHtml
    {
        return response()->html()->setView('welcome.html.twig');
    }
}