<?php

namespace System\Libraries;

class ViewHtml extends View
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct(View::VIEW);
    }

    /**
     * @param string $file
     * @return $this
     */
    public function setView(string $file): static
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return string
     */
    public function getView(): string
    {
        return $this->file;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function setParams(array $params): static
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }
}