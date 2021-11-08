<?php

namespace System\Libraries;

use Exception;

/**
 * @todo Reformular classe de validação
 */
class Validate
{
    const VARCHAR = "varchar";
    const INT = "int";
    const FLOAT = "float";
    const ONLYALPHA = "onlyAlpha";
    const ONLYALPHANUMERIC = "onlyAlphanumeric";
    const DATE = "date";
    const EMAIL = "email";
    const URL = "url";
    const MONEY = "money";
    static array $type = array('varchar', 'int', 'float', 'onlyAlpha', 'onlyAlphanumeric', 'date', 'email', 'url', 'money');
    private string $varchar;
    private string $format = "DD/MM/YYYY";

    /**
     * Validate String
     * @param string $string
     * @param array $args
     * @param string $type
     * @param string $function
     * @return mixed
     */
    public function validate(string $string, array $args = array(), string $type = 'varchar', mixed $function = ""): mixed
    {
        if (!in_array($type, Validate::$type)) {
            $type = "varchar";
        }

        $hasFunction = false;
        if (!empty($function)) {
            try {
                $this->varchar = $function($string, $args);
                $hasFunction = true;
            } catch (Exception $e) {
                $hasFunction = false;
            }
        }

        $Method = "_" . $type;
        if (method_exists($this, $Method) && !$hasFunction) $this->varchar = $this->$Method($string, $args);
        return $this->varchar;
    }

    /**
     * Validate type String
     * @param mixed $string
     * @param int|null $min
     * @param int|null $max
     * @return bool|string
     */
    private function _varchar(mixed $string, int $min = null, int $max = null): bool|string
    {
        $string = filter_var($string, FILTER_SANITIZE_STRING);

        if (is_string($string)) {
            if (!is_null($min)) if (strlen($string) >= $min) return false;
            if (!is_null($max)) if (strlen($string) <= $max) return false;
        }

        return $string;
    }

    /**
     * Validate type int
     * @param string $int $int
     * @param int|null $min
     * @param int|null $max
     * @return bool|int
     */
    private function _int(string $int, int $min = null, int $max = null): bool|int
    {
        if (!is_null($min) && is_numeric($int)) if ($int <= $min) return false;

        if (!is_null($max) && is_numeric($int)) if ($int >= $max) return false;

        if (filter_var($int, FILTER_VALIDATE_INT)) return $int;

        return false;
    }

    /**
     * Validate Type Float
     * @param string $float
     * @param int|null $min
     * @param int|null $max
     * @return bool|string
     */
    private function _float(string $float, int $min = null, int $max = null): bool|string
    {
        if (!is_null($min) && is_numeric($float)) if ($float < $min) return false;

        if (!is_null($max) && is_numeric($float)) if ($float > $max) return false;

        if (filter_var($float, FILTER_VALIDATE_FLOAT)) return $float;

        return false;
    }

    /**
     * Validate Only Alpha
     * @param $str
     * @param $args
     * @return bool
     */
    private function _onlyAlpha($str, $args)
    {
        if (isset($args['min']) && is_numeric($args['min'])) {
            if (!isset($str[$args['min'] - 1])) {
                return false;
            }
        }
        if (isset($args['max']) && is_numeric($args['max'])) {
            if (isset($str[$args['max']])) {
                return false;
            }
        }
        if (ctype_alpha($str)) {
            return $str;
        }
        return false;
    }

    /**
     * Validate Alphanumeric
     * @param $str
     * @param $args
     * @return bool
     */
    private function _onlyAlphanumeric($str, $args)
    {
        if (isset($args['min']) && is_numeric($args['min'])) {
            if (!isset($str[$args['min'] - 1])) {
                return false;
            }
        }
        if (isset($args['max']) && is_numeric($args['max'])) {
            if (isset($str[$args['max']])) {
                return false;
            }
        }
        if (ctype_alnum($str)) {
            return $str;
        }
        return false;
    }

    /**
     * Validate Date
     * @param $date
     * @param $args
     * @return bool
     */
    private function _date($date, $args)
    {
        if (isset($args['format'])) {
            $this->format = $args['format'];
        }

        switch ($this->format) {
            case 'YYYY/MM/DD':
            case 'YYYY-MM-DD':
                list($y, $m, $d) = preg_split('/[-\.\/ ]/', $date);
                break;
            case 'YYYY/DD/MM':
            case 'YYYY-DD-MM':
                list($y, $d, $m) = preg_split('/[-\.\/ ]/', $date);
                break;
            case 'DD-MM-YYYY':
            case 'DD/MM/YYYY':
                list($d, $m, $y) = preg_split('/[-\.\/ ]/', $date);
                break;

            case 'MM-DD-YYYY':
            case 'MM/DD/YYYY':
                list($m, $d, $y) = preg_split('/[-\.\/ ]/', $date);
                break;

            case 'YYYYMMDD':
                $y = substr($date, 0, 4);
                $m = substr($date, 4, 2);
                $d = substr($date, 6, 2);
                break;

            case 'YYYYDDMM':
                $y = substr($date, 0, 4);
                $d = substr($date, 4, 2);
                $m = substr($date, 6, 2);
                break;

            default:
                return false;
        }
        if (checkdate($m, $d, $y)) {
            return $date;
        }
        return false;
    }

    /**
     * Validate E-mail format
     * @param $value
     * @param $args
     * @return bool
     */
    private function _email($value, $args)
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return $value;
        }
        return false;
    }

    /**
     * Validate format URL
     * @param $value
     * @param $args
     * @return bool
     */
    private function _url($value, $args)
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        return false;
    }

    /**
     * Validate Money format
     * @param $value
     * @param $args
     * @return bool
     */
    private function _money($value, $args)
    {
        preg_match("/\b\d{1,3}(?:,?\d{3})*(?:\.\d{2})?\b/", $value, $From);
        if (isset($From[0])) {
            return $From[0];
        }
        return false;
    }

}