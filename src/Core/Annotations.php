<?php
namespace Intraxia\Jaxion\Core\Annotation;

/**
 * Annotation class for actions.
 *
 * @Annotation
 * @Target({"METHOD"})
 */
class Action {
	/**
	 * Hook to attach method to.
	 *
	 * @var string
	 * @Required
	 */
	public $hook;

	/**
	 * Priority level for the hook.
	 *
	 * @var int
	 */
	public $priority = 10;

	/**
	 * Arguments to call the hook with.
	 *
	 * @var int
	 */
	public $args = 1;
}

/**
 * Annotation class for filters.
 *
 * @Annotation
 * @Target({"METHOD"})
 */
class Filter {
	/**
	 * Hook to attach method to.
	 *
	 * @var string
	 * @Required
	 */
	public $hook;

	/**
	 * Priority level for the hook.
	 *
	 * @var int
	 */
	public $priority = 10;

	/**
	 * Arguments to call the hook with.
	 *
	 * @var int
	 */
	public $args = 1;
}
