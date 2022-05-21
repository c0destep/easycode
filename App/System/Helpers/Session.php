<?php

use System\Libraries\Session;

if (!function_exists('setFlashError')) {
    /**
     * Assigning error message.
     * @param string $message
     */
    function setFlashError(string $message): void
    {
        Session::getInstance()->setFlash("error", $message);
    }
}

if (!function_exists('getFlashError')) {
    /**
     * If there is error message it shows.
     * @return null|string
     */
    function getFlashError(): ?string
    {
        $error = Session::getInstance()->getFlash("error");
        return $error ?? null;
    }
}

if (!function_exists('setFlashSuccess')) {
    /**
     * Assigning success message.
     * @param string $message
     */
    function setFlashSuccess(string $message): void
    {
        Session::getInstance()->setFlash("success", $message);
    }
}

if (!function_exists('getFlashSuccess')) {
    /**
     * If there is success message it shows.
     * @return null|string
     */
    function getFlashSuccess(): ?string
    {
        $success = Session::getInstance()->getFlash("success");
        return $success ?? null;
    }
}

if (!function_exists('setFlashWarning')) {
    /**
     * Assigning alert message.
     * @param string $message
     */
    function setFlashWarning(string $message): void
    {
        Session::getInstance()->setFlash("warning", $message);
    }
}

if (!function_exists('getFlashWarning')) {
    /**
     * If there is warning message it shows.
     * @return null|string
     */
    function getFlashWarning(): ?string
    {
        $warning = Session::getInstance()->getFlash("warning");
        return $warning ?? null;
    }
}

if (!function_exists('setFlashInfo')) {
    /**
     * Assigning alert message.
     * @param string $message
     */
    function setFlashInfo(string $message): void
    {
        Session::getInstance()->setFlash("info", $message);
    }
}

if (!function_exists('getFlashInfo')) {
    /**
     * If there is info message it shows.
     * @return null|string
     */
    function getFlashInfo(): ?string
    {
        $info = Session::getInstance()->getFlash("info");
        return $info ?? null;
    }
}