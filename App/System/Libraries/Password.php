<?php

namespace System\Libraries;

class Password
{

    protected static $cost = 10;

    public static function checkCost()
    {
        $timeTarget = 0.05;
        $cost = 8;
        do {
            $cost++;
            $start = microtime(true);
            password_hash("teste", PASSWORD_BCRYPT, ["cost" => $cost]);
            $end = microtime(true);
        } while (($end - $start) < $timeTarget);
        echo "Appropriate Cost Found: " . $cost;
    }

    /**
     * @param $passworld
     * @return false|string
     */
    public static function getPassworld($passworld)
    {
        return password_hash($passworld, PASSWORD_BCRYPT, [
            "cost" => self::$cost
        ]);
    }

    /**
     * @param $passworld
     * @param $hash
     * @return bool
     */
    public static function verifyPassworld($passworld, $hash)
    {
        return password_verify($passworld, $hash);
    }

    /**
     * @return string
     */
    public static function generateUuid()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0C2f) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0x2Aff), mt_rand(0, 0xffD3), mt_rand(0, 0xff4B)
        );
    }
}