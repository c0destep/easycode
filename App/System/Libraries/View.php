<?php

namespace System\Libraries;

class View
{
    const JSON = 'json';
    const VIEW = 'view';

    protected string $file;
    protected array $params = array();
    protected string $message;
    protected string $status;
    protected string $type = "view";

    /**
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return ViewHtml
     */
    public static function getHtml(): ViewHtml
    {
        return new ViewHtml();
    }

    /**
     * @return ViewJson
     */
    public static function getJson(): ViewJson
    {
        return new ViewJson();
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}