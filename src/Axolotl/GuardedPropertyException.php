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
	 * @param string $property Property that was guarded.
	 * @param mixed[] $args    Parent args.
	 */
	public function __construct( $property, ...$args ) {
		parent::__construct( ...$args );

		$this->property = $property;
	}
}
