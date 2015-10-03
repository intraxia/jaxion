<?php
namespace Intraxia\Jaxion\Utility;

/**
 * Class Str
 *
 * String utility class. Much of this has been borrowed from Illuminate\Support
 * and dumbed down for PHP 5.3 compatibility.
 *
 * @package Intraxia\Jaxion
 * @subpackage Utility
 */
class Str
{
    /**
     * The cache of studly-cased words.
     *
     * @var array
     */
    protected static $studlyCache = array();

    /**
     * Determine if a given string starts with a given substring.
     *
     * @param  string       $haystack
     * @param  string|array $needles
     * @return bool
     */
    public static function startsWith($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ($needle != '' && strpos($haystack, $needle) === 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if a given string ends with a given substring.
     *
     * @param  string       $haystack
     * @param  string|array $needles
     * @return bool
     */
    public static function endsWith($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ((string) $needle === substr($haystack, -strlen($needle))) {
                return true;
            }
        }
        return false;
    }

    /**
     * Convert a value to studly caps case.
     *
     * @param string $value
     * @return string
     */
    public static function studly($value)
    {
        $key = $value;

        if (isset(static::$studlyCache[$key])) {
            return static::$studlyCache[$key];
        }

        $value = ucwords(str_replace(array('-', '_'), ' ', $value));

        return static::$studlyCache[$key] = str_replace(' ', '', $value);
    }
}
