<?php

namespace System\Libraries;

class Password
{
    protected static int $cost = 10;

    public static function checkCost(): void
    {
        $timeTarget = 0.05;
        $cost = self::$cost;
        do {
            $cost++;
            $start = microtime(true);
            password_hash("test", PASSWORD_BCRYPT, ["cost" => $cost]);
            $end = microtime(true);
        } while (($end - $start) < $timeTarget);
        echo "Appropriate Cost Found: " . $cost;
    }

    /**
     * @param string $password
     * @return string
     */
    public static function encryptPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, [
            "cost" => self::$cost
        ]);
    }

    /**
     * @param string $password
     * @param string $encryptedPassword
     * @return bool
     */
    public static function verifyPassword(string $password, string $encryptedPassword): bool
    {
        return password_verify($password, $encryptedPassword);
    }

    /**
     * @return string
     */
    public static function generateUuid(): string
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