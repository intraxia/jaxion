<?php
namespace Intraxia\Jaxion\Contract\Axolotl;

use Countable;
use Iterator;
use InvalidArgumentException;
use OutOfBoundsException;
use OutOfRangeException;

interface Collection extends Iterator, Countable, Serializes {

	/**
	 * Returns the type of the collection.
	 *
	 * @return string
	 */
	public function get_type();

	/**
	 * Returns a collection with $element added.
	 *
	 * @param  mixed $element
	 * @return Collection
	 * @throws InvalidArgumentException
	 */
	public function add( $element );

	/**
	 * Removes every element from the collection.
	 *
	 * @return Collection
	 */
	public function clear();

	/**
	 * Returns true if the collection contains any elements that satisfy
	 * $condition, returns false if it contains none.
	 *
	 * @param callable $condition
	 * @return bool
	 */
	public function contains( callable $condition );

	/**
	 * Returns the first element in the collection that satisfies
	 * $condition, returns false if no such element exists.
	 *
	 * @param callable $condition
	 * @return mixed
	 */
	public function find( callable $condition );

	/**
	 * Returns the index of the first element in the collection that satisfies
	 * $condition, returns -1 if no such element exists.
	 *
	 * @param callable $condition
	 * @return int
	 */
	public function find_index( callable $condition );

	/**
	 * Returns the element in the collection at $index.
	 *
	 * @param  int $index Index to get element from.
	 * @return mixed
	 * @throws OutOfRangeException
	 */
	public function at( $index );

	/**
	 * Returns true if $index is within the collection's range and returns false
	 * if it is not.
	 *
	 * @param  int $index Index to check for existence.
	 * @return bool
	 * @throws InvalidArgumentException
	 */
	public function index_exists( $index );

	/**
	 * Returns the number of elements in the collection.
	 *
	 * @return int
	 */
	public function count();

	/**
	 * Returns a collection that only contains the elements which satisfy
	 * $condition.
	 *
	 * @param callable $condition
	 * @return Collection
	 */
	public function filter( callable $condition );

	/**
	 * Returns the last element in the collection that satisfies $condition,
	 * returns false if no such element exists.
	 *
	 * @param callable $condition
	 * @return mixed
	 */
	public function find_last( callable $condition );

	/**
	 * Returns the index of the last element in the collection that satisfies
	 * $condition, returns -1 if no such element exists.
	 *
	 * @param callable $condition
	 * @return int
	 */
	public function find_last_index( callable $condition );

	/**
	 * Returns a collection that contains the subset of elements ranging from the
	 * index $start to $end.
	 *
	 * @param  int $start Begining index to slice from.
	 * @param  int $end   End index to slice to.
	 * @return Collection
	 * @throws InvalidArgumentException
	 */
	public function slice( $start, $end );

	/**
	 * Inserts $element at $index.
	 *
	 * @param int   $index     Index to start at.
	 * @param mixed $element Element to insert.
	 * @throws InvalidArgumentException
	 * @throws OutOfRangeException
	 */
	public function insert( $index, $element );

	/**
	 * Inserts the range $elements at $index.
	 *
	 * @param int   $index
	 * @param array $elements
	 * @throws OutOfRangeException
	 */
	public function insert_range( $index, array $elements );

	/**
	 * Removes all of the elements that satisfy $condition.
	 *
	 * @param  callable $condition
	 * @return Collection
	 */
	public function without( callable $condition );

	/**
	 * Removes the element at $index.
	 *
	 * @param  int $index Index to remove.
	 * @return Collection
	 * @throws OutOfRangeException
	 */
	public function remove_at( $index );

	/**
	 * Reverses the order of the elements in the collection.
	 *
	 * @return Collection
	 */
	public function reverse();

	/**
	 * Sorts the elements in the collection using the user supplied comparison
	 * function $callback.
	 *
	 * @param callable $callback
	 * @return Collection
	 */
	public function sort( callable $callback );

	/**
	 * Returns an array containing the elements in the collection.
	 *
	 * @return array
	 */
	public function to_array();

	/**
	 * Iteratively reduces the collection to a single value using the callback
	 * function $callable.
	 *
	 * @param callable $callable
	 * @param null     $initial
	 * @return mixed
	 */
	public function reduce( callable $callable, $initial = null );

	/**
	 * Returns true if every element in the collection satisfies $condition,
	 * returns false if not.
	 *
	 * @param callable $condition
	 * @return bool
	 */
	public function every( callable $condition );

	/**
	 * Removes all of the elements in the collection starting at index $num.
	 *
	 * @param  int $num Number of elements to drop.
	 * @return Collection
	 * @throws InvalidArgumentException
	 */
	public function drop( $num );

	/**
	 * Removes all of the elements in the collectioin between index 0 and $num.
	 *
	 * @param int $num Number of elements to drop.
	 * @return Collection
	 * @throws InvalidArgumentException
	 */
	public function drop_right( $num );

	/**
	 * Iteratively drops elements in the collection that satisfy $condition until
	 * an element is encountered that does not satisfy $condition.
	 *
	 * @param callable $condition
	 * @return Collection
	 */
	public function drop_while( callable $condition );

	/**
	 * Removes the first element in the collection.
	 *
	 * @return Collection
	 * @throws InvalidArgumentException
	 */
	public function tail();

	/**
	 * Removes all of the elements in the collection starting at index $num.
	 *
	 * @param  int $num Number of elements to take.
	 * @return Collection
	 * @throws InvalidArgumentException
	 */
	public function take( $num );

	/**
	 * Removes all of the elements in the collection before index $num.
	 *
	 * @param int $num Number of elements to take.
	 * @return Collection
	 * @throws InvalidArgumentException
	 */
	public function take_right( $num );

	/**
	 * Iterates through the collection until an element is encountered that does
	 * not satisfy $condition, then drops all of the elements starting at that
	 * index.
	 *
	 * @param callable $condition
	 * @return Collection
	 */
	public function take_while( callable $condition );

	/**
	 * Applies the callback function $callable to each element in the collection.
	 *
	 * @param callable $callable
	 */
	public function each( callable $callable );

	/**
	 * Returns a new instance of the collection with the callback function
	 * $callable applied to each element.
	 *
	 * @param callable $callable
	 * @return Collection
	 */
	public function map( callable $callable );

	/**
	 * Iteratively reduces the collection to a single value using the callback
	 * function $callable starting at the rightmost index.
	 *
	 * @param callable $callable
	 * @param null     $initial
	 * @return mixed
	 */
	public function reduce_right( callable $callable, $initial = null );

	/**
	 * Randomly reorders the elements in the collection.
	 *
	 * @return Collection
	 */
	public function shuffle();

	/**
	 * Adds every member of $elements to the collection.
	 *
	 * @param array $elements Array of elements to merge.
	 * @return Collection
	 * @throws InvalidArgumentException
	 */
	public function merge( array $elements );

	/**
	 * Get first element of the collection
	 *
	 * @return mixed
	 * @throws OutOfBoundsException
	 */
	public function first();

	/**
	 * Get last element of the collection
	 *
	 * @return mixed
	 * @throws OutOfBoundsException
	 */
	public function last();
}
