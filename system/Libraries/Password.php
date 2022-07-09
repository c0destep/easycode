<?php

namespace System\Libraries;

class Password
{
    protected static int $cost;

    public function __construct(int $cost = 12)
    {
        self::$cost = $cost;
    }

    /**
     * @param string $password
     * @return string
     */
    public function encryptPassword(string $password): string
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
    public function verifyPassword(string $password, string $encryptedPassword): bool
    {
        return password_verify($password, $encryptedPassword);
    }
}