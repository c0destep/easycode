<?php

use System\Libraries\Session;

if (!function_exists('setFlashError')) {
    /**
     * Assigning error message.
     * @param $message
     */
    function setFlashError($message)
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
        $Error = Session::getInstance()->getFlash("error");

        return (!is_null($Error)) ? $Error : null;
    }
}

if (!function_exists('setFlashSuccess')) {
    /**
     * Assigning success message.
     * @param $message
     */
    function setFlashSuccess($message)
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
        $Success = Session::getInstance()->getFlash("success");

        return (!is_null($Success)) ? $Success : null;
    }
}

if (!function_exists('setFlashWarning')) {
    /**
     * Assigning alert message.
     * @param $message
     */
    function setFlashWarning($message)
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
        $Warning = Session::getInstance()->getFlash("warning");

        return (!is_null($Warning)) ? $Warning : null;
    }
}