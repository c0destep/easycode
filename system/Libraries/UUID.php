<?php
/**
 * Class obtida em:
 * http://php.net/manual/pt_BR/function.uniqid.php
 * @author Andrew Moore
 */

namespace System\Libraries;

class UUID
{
    /**
     * @param string $namespace
     * @param string $name
     * @return string|null
     */
    public static function v3(string $namespace, string $name): ?string
    {
        if (!self::is_valid($namespace)) {
            return null;
        } else {
            $nhex = str_replace(array('-', '{', '}'), '', $namespace);
            $nstr = "";

            for ($i = 0; $i < strlen($nhex); $i += 2) {
                $nstr .= chr(hexdec($nhex[$i] . $nhex[$i + 1]));
            }

            $hash = md5($nstr . $name);

            return sprintf('%08s-%04s-%04x-%04x-%12s',
                substr($hash, 0, 8),
                substr($hash, 8, 4),
                (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x3000,
                (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,
                substr($hash, 20, 12)
            );
        }
    }

    /**
     * @param string $uuid
     * @return bool
     */
    public static function is_valid(string $uuid): bool
    {
        return preg_match('/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?' . '[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i', $uuid) === 1;
    }

    /**
     * @return string
     */
    public static function v4(): string
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    /**
     * @param string $namespace
     * @param string $name
     * @return string|null
     */
    public static function v5(string $namespace, string $name): ?string
    {
        if (!self::is_valid($namespace)) {
            return null;
        } else {
            $nhex = str_replace(array('-', '{', '}'), '', $namespace);
            $nstr = '';
            for ($i = 0; $i < strlen($nhex); $i += 2) {
                $nstr .= chr(hexdec($nhex[$i] . $nhex[$i + 1]));
            }

            $hash = sha1($nstr . $name);

            return sprintf('%08s-%04s-%04x-%04x-%12s',
                substr($hash, 0, 8),
                substr($hash, 8, 4),
                (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x5000,
                (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,
                substr($hash, 20, 12)
            );
        }
    }

}