<?php
namespace Intraxia\Jaxion\Core;

/**
 * Trait Annotatable.
 *
 * Satisfies the Has{Actions,Filters} interface using method annotations.
 *
 * @package    Intraxia\Jaxion
 * @subpackage Core
 */
trait Annotatable {

	/**
	 * Read the class methods and return the filter hooks.
	 *
	 * @return array
	 */
	public function filter_hooks() {
		return HooksReader::read( $this, function( $annotation ) {
			return $annotation instanceof Annotation\Filter;
		});
	}

	/**
	 * Read the class methods and return the action hooks.
	 *
	 * @return array
	 */
	public function action_hooks() {
		return HooksReader::read( $this, function( $annotation ) {
			return $annotation instanceof Annotation\Action;
		});
	}
}
