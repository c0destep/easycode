<?php

namespace Controller;

use System\Core\Controller;
use System\Libraries\ViewHtml;
use System\Request;
use System\Response;

class Index extends Controller
{
    /**
     * @param Request $request
     * @param Response $response
     * @return ViewHtml
     * When the method has $request and $response in the parameters,
     * both will automatically assume class System\Response and System\Request
     */
    public function Index(Response $response, Request $request): ViewHtml
    {
        return $response->html()->setView("welcome");
    }

    /**
     * Ex Dynamics urls
     * We can retrieve the dynamic value with same name parameters
     * Ex:  @route find/{id} : {id} -> $id
     * When the method has $request and $response in the parameters,
     * both will automatically assume class System\Response and System\Request
     * @param int $id dynamic value in url {id}
     */
    public function FindExample(Response $response, Request $request, int $id): ViewHtml
    {
        return $response->html()->setView("welcome")->setParams([
            "Id" => $id
        ]);
    }
}