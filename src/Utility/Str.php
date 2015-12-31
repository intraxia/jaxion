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
class Str {
	/**
	 * Determine if a given string starts with a given substring.
	 *
	 * @param  string       $haystack
	 * @param  string|array $needles
	 *
	 * @return bool
	 */
	public static function starts_with( $haystack, $needles ) {
		foreach ( (array) $needles as $needle ) {
			if ( $needle != '' && strpos( $haystack, $needle ) === 0 ) {
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
	 *
	 * @return bool
	 */
	public static function ends_with( $haystack, $needles ) {
		foreach ( (array) $needles as $needle ) {
			if ( (string) $needle === substr( $haystack, - strlen( $needle ) ) ) {
				return true;
			}
		}

		return false;
	}
}
