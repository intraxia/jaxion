<?php
namespace Intraxia\Jaxion\Axolotl;

use RuntimeException;

/**
 * Class GuardedPropertyException
 *
 * @package    Intraxia\Jaxion
 * @subpackage Axolotl
 */
class GuardedPropertyException extends RuntimeException {
	/**
	 * Property that threw.
	 *
	 * @var string
	 */
	public $property;

	/**
	 * Construct a GuardedPropertyException.
	 *
	 * @param string $property Property that was guarded.
	 */
	public function __construct( $property ) {
		parent::__construct();

		$this->property = $property;
	}
}
