<?php

namespace App\Controllers;

use Easycode\Http\Request;
use Easycode\Routing\Controller;
use Easycode\View\ViewHtml;

class IndexController extends Controller
{
    /**
     * @param Request $request
     * @return ViewHtml
     * When the method has $request and $response in the parameters,
     * both will automatically assume class System\Response and System\Request
     */
    public function index(Request $request): ViewHtml
    {
        return response()->html()->setView('welcome');
    }

    /**
     * Ex Dynamics urls
     * We can retrieve the dynamic value with same name parameters
     * Ex: @route find/{id} : {id} -> $id
     * When the method has $request and $response in the parameters,
     * both will automatically assume class System\Response and System\Request
     * @param int $id dynamic value in url {id}
     */
    public function findUser(Request $request, int $id): ViewHtml
    {
        return response()->html()->setView('welcome')->setParameters(['id' => $id]);
    }
}