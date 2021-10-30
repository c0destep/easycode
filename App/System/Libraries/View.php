<?php

namespace System\Libraries;

class View
{
    const JSON = 'json';
    const VIEW = 'view';

    protected $file;
    protected $params = [];
    protected $message;
    protected $status;
    protected $type = "view";

    public function __construct($type)
    {
        $this->type = $type;
    }

    public static function getHtml()
    {
        return new ViewHtml();
    }

    public static function getJson()
    {
        return new ViewJson();
    }

    public function getType()
    {
        return $this->type;
    }

}

class ViewHtml extends View
{

    public function __construct()
    {
        parent::__construct(View::VIEW);
    }

    /**
     * @param string $file
     * @return $this
     */
    public function setView(string $file)
    {
        $this->file = $file;
        return $this;
    }

    public function getView()
    {
        return $this->file;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }

    public function getParams()
    {
        return $this->params;
    }


}

class ViewJson extends View
{

    public function __construct()
    {
        parent::__construct(View::JSON);
    }

    public function setStatus(string $status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @param array|string $params
     * @return $this
     */
    public function setResponse($params)
    {
        $this->params = $params;
        return $this;
    }

    public function toJson()
    {
        return json_encode(["status" => $this->getStatus(), "message" => $this->getMessage(), "response" => $this->getResponse()]);
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getResponse()
    {
        return $this->params;
    }
}
