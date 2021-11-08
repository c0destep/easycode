<?php

namespace System\Libraries;

class ViewJson extends View
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct(View::JSON);
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): static
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message): static
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @param array|string $params
     * @return $this
     */
    public function setResponse(array|string $params): static
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @return bool|string
     */
    public function toJson(): bool|string
    {
        return json_encode(["status" => $this->getStatus(), "message" => $this->getMessage(), "response" => $this->getResponse()]);
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return array
     */
    public function getResponse(): array
    {
        return $this->params;
    }
}