<?php

namespace System\Libraries;

class Hash
{
    protected static int $cost;
    private static Hash $instance;

    private function __construct(int $cost = null)
    {
        self::$cost = $cost ?? 12;
    }

    public static function getInstance(int $cost = null): Hash
    {
        if (!isset(self::$instance)) {
            self::$instance = new self($cost);
        }

        return self::$instance;
    }

    /**
     * @param string $password
     * @return string
     */
    public static function encryptPassword(string $password): string
    {
        if (empty($password)) {
            return $password;
        }

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
        if (empty($password) || empty($encryptedPassword)) {
            return false;
        }

        return password_verify($password, $encryptedPassword);
    }

    private function __wakeup(): void
    {
    }

    private function __clone(): void
    {
    }
}