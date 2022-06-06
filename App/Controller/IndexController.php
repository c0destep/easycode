<?php

namespace Controller;

use Models\User;
use System\Core\Controller;
use System\Libraries\ViewHtml;
use System\Response;

class IndexController extends Controller
{
    /**
     * @param Response $response
     * @return ViewHtml
     * When the method has $request and $response in the parameters,
     * both will automatically assume class System\Response and System\Request
     */
    public function Index(Response $response): ViewHtml
    {
        return $response->html()->setView("welcome");
    }

    /**
     * Ex Dynamics urls
     * We can retrieve the dynamic value with same name parameters
     * Ex: @route find/{id} : {id} -> $id
     * When the method has $request and $response in the parameters,
     * both will automatically assume class System\Response and System\Request
     * @param int $id dynamic value in url {id}
     */
    public function FindUser(Response $response, int $id): ViewHtml
    {
        return $response->html()->setView("welcome")->setParams(User::find($id));
    }
}