<?php

if (!function_exists('trimText')) {
    /**
     * Cortar string em tamanho especifico
     * @param string $text
     * @param int $limit
     * @return string
     */
    function trimText(string $text, int $limit = 120): string
    {
        $text = strip_tags($text);
        if (strlen($text) > $limit) {
            $stringCut = substr($text, 0, $limit);
            $endPoint = strrpos($stringCut, ' ');
            $text = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
            $text .= '...';
        }
        return $text;
    }
}


if (!function_exists("trimWords")) {
    /**
     * @param string $text
     * @param int $limit
     * @return string
     */
    function trimWords(string $text, int $limit = 15): string
    {
        $words = explode(' ', $text);
        if (count($words) > $limit) {
            return implode(' ', array_slice($words, 0, $limit)) . "...";
        }
        return $text;
    }
}

if (!function_exists("truncateText")) {
    /**
     * @param string $text
     * @param int $limit
     * @param string $break
     * @param string $pad
     * @return string
     */
    function truncateText(string $text, int $limit, string $break = ".", string $pad = "..."): string
    {
        if (strlen($text) <= $limit) return $text;
        if (false !== ($max = strpos($text, $break, $limit))) {
            if ($max < strlen($text) - 1) {
                $text = substr($text, 0, $max) . $pad;
            }
        }
        return $text;
    }
}

if (!function_exists("random_str")) {
    /**
     * Generate a random string, using a cryptographically secure
     * pseudorandom number generator (random_int)
     *
     * This function uses type hints now (PHP 7+ only), but it was originally
     * written for PHP 5 as well.
     *
     * For PHP 7, random_int is a PHP core function
     * For PHP 5.x, depends on https://github.com/paragonie/random_compat
     *
     * @param int $length How many characters do we want?
     * @param string $keyspace A string of all possible characters
     *                         to select from
     * @return string
     * @throws Exception
     */
    function random_str(int $length = 64, string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'): string
    {
        if ($length < 1) {
            throw new RangeException("Length must be a positive integer");
        }
        $pieces = "";
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces .= $keyspace[random_int(0, $max)];
        }
        return $pieces;
    }
}