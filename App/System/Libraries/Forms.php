<?php

namespace System\Libraries;

use Exception;
use System\Request;

class Forms
{
    protected static Forms $instance;
    protected array $inputs = array();
    protected array $errors = array();
    protected array $getFields = array();

    public function __construct()
    {
        self::$instance = $this;
    }

    public static function getInstance(): Forms
    {
        self::$instance = new Forms();
        return self::$instance;
    }

    /**
     * @param string $key
     * @param bool $required
     * @param mixed $functionRule
     * @param mixed $args
     * @param string|null $messageError
     */
    public function setRules(string $key, bool $required = false, mixed $functionRule = null, mixed $args = null, string $messageError = null)
    {
        $this->inputs[$key] = [$required, $functionRule, $args, $messageError];
    }

    /**
     * Generate random code to return on ajax response
     * @param string $nameForm
     * @param int $sizeCodeSecurity
     * @return string
     */
    public function initJson(string $nameForm, int $sizeCodeSecurity = 8): string
    {
        $codeForm = randomCode($sizeCodeSecurity);
        Session::getInstance()->setFlash($nameForm, $codeForm);
        return $codeForm;
    }

    /**
     * @param string $nameForm
     * @param string $nameRoute
     * @param string $method
     * @param bool $fileUpload
     * @param int $sizeCodeSecurity
     * @return string
     */
    public function init(string $nameForm, string $nameRoute, string $method, bool $fileUpload = false, int $sizeCodeSecurity = 8): string
    {
        $codeForm = randomCode($sizeCodeSecurity);
        $route = route($nameRoute);
        Session::getInstance()->setFlash($nameForm, $codeForm);
        $fileUpload ? $enctype = "application/x-www-form-urlencoded" : $enctype = "multipart/form-data";
        return "<form id='$nameForm' action='$route' method='$method' enctype='$enctype'><input type='hidden' name='$nameForm' value='$codeForm'>";
    }

    public function end(): string
    {
        return "</form>";
    }

    /**
     * @param string|null $nameForm
     * @param string $method
     * @param bool $xss
     * @throws Exception
     */
    public function validate(string $nameForm = null, string $method = "POST", bool $xss = false): void
    {
        $Method = match ($method) {
            Request::GET => "get",
            Request::REQUEST => "request",
            Request::JSON => "json",
            Request::EXTRA => "extra",
            default => "post",
        };

        if (!is_null($nameForm)) {
            $TokenForm = Request::getInstance()->$Method($nameForm);
            if (!$this->validToken($nameForm, $TokenForm)) {
                $this->errors[$nameForm] = Lang::get("form_invalid_token");
                return;
            }
        }

        foreach ($this->inputs as $input => $args) {
            $Value = Request::getInstance()->$Method($input, $xss);
            $this->getFields[$input] = $Value;
            try {
                $isRequire = $args[0];
                if ($isRequire && empty($Value)) {
                    if (!is_null($args[3])) {
                        $msgError = $args[3];
                    } else {
                        $msgError = Lang::get("form_require", ":attr:", Lang::get("input_$input"));
                    }
                    $this->errors[$input] = $msgError;
                } else {
                    if (!$isRequire && empty($Value)) continue;

                    if (is_array($args[1])) {
                        $Class = $args[1][0];
                        $ValidMethod = $args[1][1];
                        $isValid = $Class->$ValidMethod($Value, $args[2]);
                    } else {
                        $FunctionTry = $args[1];
                        $isValid = $FunctionTry($Value, $args[2]);
                    }

                    if (!$isValid) {
                        if (!is_null($args[3])) {
                            $msgError = $args[3];
                        } else {
                            $msgError = Lang::get("input_error_$input");
                        }
                        $this->errors[$input] = $msgError;
                    }
                }
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
    }

    /**
     * @param string $nameForm
     * @param $token string token form
     * @return bool
     */
    private function validToken(string $nameForm, string $token): bool
    {
        $codeForm = Session::getInstance()->getFlash($nameForm);
        return ($codeForm === $token);
    }

    /**
     * Get Validate Fields
     * @param string|null $key string
     * @return array|string|null
     */
    public function getFields(string $key = null): array|string|null
    {
        if (!is_null($key)) {
            if (!isset($this->getFields[$key]) || empty($this->getFields[$key])) return null;
            return $this->getFields[$key];
        }
        return $this->getFields;
    }

    /**
     * Verifica se possuí erros no formulário
     * @return bool
     */
    public function hasErrors(): bool
    {
        return (count($this->errors) > 0);
    }

    /**
     * Obter todos os erros
     * @return array Lista de erros
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
