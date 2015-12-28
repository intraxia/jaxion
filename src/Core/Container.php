<?php
namespace Intraxia\Jaxion\Core;

use Intraxia\Jaxion\Contract\Core\Container as ContainerContract;

/**
 * Class Container
 *
 * Contains, manages, and retrieves service objects.
 *
 * @package Intraxia\Jaxion
 * @subpackage Core
 */
class Container implements ContainerContract {
	/**
	 * Registered definitions.
	 *
	 * @var mixed[]
	 */
	private $definitions = array();

	/**
	 * Aliases to share between fetches.
	 *
	 * @var <string, true>[]
	 */
	private $shared = array();

	/**
	 * Aliases of all the registered services.
	 *
	 * @var <string, true>[]
	 */
	private $aliases = array();

	/**
	 * Current position in the loop.
	 *
	 * @var int
	 */
	private $position;

	/**
	 * 0-indexed array of aliases for looping.
	 *
	 * @var string[]
	 */
	private $keys = array();

	/**
	 * {@inheritdoc}
	 *
	 * @param string|array $alias
	 * @param mixed        $definition
	 *
	 * @throws DefinedAliasException
	 *
	 * @return $this
	 */
	public function define( $alias, $definition ) {
		if ( is_array( $alias ) ) {
			$class = current( $alias );
			$alias = key( $alias );
		}

		if ( isset( $this->aliases[ $alias ] ) ) {
			throw new DefinedAliasException;
		}

		$this->aliases[ $alias ]     = true;
		$this->definitions[ $alias ] = $definition;

		// Closures are treated as factories unless
		// defined via Container::share.
		if ( ! $definition instanceof \Closure ) {
			$this->shared[ $alias ] = true;
		}

		if ( isset( $class ) ) {
			$this->classes[ $class ] = $alias;
		}

		return $this;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @param string|array $alias
	 * @param mixed        $definition
	 *
	 * @throws DefinedAliasException
	 *
	 * @return $this
	 */
	public function share( $alias, $definition ) {
		$this->define( $alias, $definition );

		if ( is_array( $alias ) ) {
			$alias = key( $alias );
		}

		$this->shared[ $alias ] = true;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @param string $alias
	 *
	 * @throws UndefinedAliasException
	 *
	 * @return mixed
	 */
	public function fetch( $alias ) {
		if ( isset( $this->classes[ $alias ] ) ) {
			// If the alias is a class name,
			// then retrieve its linked alias.
			// This is only registered when
			// registering using an array.
			$alias = $this->classes[ $alias ];
		}

		if ( ! isset( $this->aliases[ $alias ] ) ) {
			throw new UndefinedAliasException;
		}

		$value = $this->definitions[ $alias ];

		// If the shared value is a closure,
		// execute it and assign the result
		// in place of the closure.
		if ( $value instanceof \Closure ) {
			$factory = $value;
			$value   = $factory( $this );
		}

		// If the value is shared, save the shared value.
		if ( isset( $this->shared[ $alias ] ) ) {
			$this->definitions[ $alias ] = $value;
		}

		// Return the fetched value.
		return $value;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @param  string $alias
	 *
	 * @return bool
	 */
	public function has( $alias ) {
		return isset( $this->aliases[ $alias ] );
	}

	/**
	 * {@inheritDoc}
	 *
	 * @param string $alias
	 */
	public function remove( $alias ) {
		if ( isset( $this->aliases[ $alias ] ) ) {
			/**
			 * If there's no reference in the aliases array,
			 * the service won't be found on fetching and
			 * can be overwritten on setting.
			 *
			 * Pros: Quick setting/unsetting, faster
			 * performance on those operations when doing
			 * a lot of these.
			 *
			 * Cons: Objects and values set in the container
			 * can't get garbage collected.
			 *
			 * If this is a problem, this may need to be revisited.
			 */
			unset( $this->aliases[ $alias ] );
		}
	}

	/**
	 * Set a value into the container.
	 *
	 * @param  string $id
	 * @param  mixed  $value
	 *
	 * @see    define
	 */
	public function offsetSet( $id, $value ) {
		$this->define( $id, $value );
	}

	/**
	 * Get an value from the container.
	 *
	 * @param  string $id
	 *
	 * @return object
	 * @throws UndefinedAliasException
	 *
	 * @see    fetch
	 */
	public function offsetGet( $id ) {
		return $this->fetch( $id );
	}

	/**
	 * Checks if a key is set on the container.
	 *
	 * @param  string $id
	 *
	 * @return bool
	 *
	 * @see    has
	 */
	public function offsetExists( $id ) {
		return $this->has( $id );
	}

	/**
	 * Remove a key from the container.
	 *
	 * @param string $id
	 *
	 * @see   remove
	 */
	public function offsetUnset( $id ) {
		$this->remove( $id );
	}

	/**
	 * Sets the object properties to prepare for the loop.
	 */
	public function rewind() {
		$this->position = 0;
		$this->keys     = array_keys( $this->aliases );
	}

	/**
	 * Retrieves the service object for the current step in the loop.
	 *
	 * @return object
	 */
	public function current() {
		return $this->fetch( $this->keys[ $this->position ] );
	}

	/**
	 * Retrieves the key for the current step in the loop.
	 *
	 * @return string
	 */
	public function key() {
		return $this->keys[ $this->position ];
	}

	/**
	 * Increments to the next step in the loop.
	 */
	public function next() {
		$this->position ++;
	}

	/**
	 * Checks if the next step in the loop in valid.
	 *
	 * @return bool
	 */
	public function valid() {
		return isset( $this->keys[ $this->position ] );
	}
}
