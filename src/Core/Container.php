<?php
namespace Intraxia\Jaxion\Core;

use Intraxia\Jaxion\Contract\Core\Container as ContainerContract;

class Container implements ContainerContract
{
    /**
     * Application services, or closures to generate them.
     *
     * @var \Closure[]|object[]
     */
    private $values = array();

    /**
     * Raw Closures used to generate services.
     *
     * @var \Closure[]
     */
    private $raw = array();

    /**
     * IDs of all the generated services.
     *
     * @var bool[]
     */
    private $frozen = array();

    /**
     * IDs of all the registered services.
     *
     * @var <string>bool[]
     */
    private $keys = array();

    /**
     * Protected variables registered in the container.
     *
     * @var <string>mixed[]
     */
    private $protected = array();

    /**
     * Keys of all the registered services.
     *
     * @var string[]
     */
    private $iterableKeys;

    /**
     * Current position in the loop.
     *
     * @var int
     */
    private $position = 0;

    /**
     * Set an object into the Application container.
     *
     * Services must be defined as Closure that returns the service you want to attach to the
     * Application. If anything else is provided, that will be registered as protected and can't
     * be overridden.
     *
     * @param  string $id
     * @param  \Closure $value
     * @throws \RuntimeException
     */
    public function offsetSet($id, $value)
    {
        if ($value === null || isset($this->frozen[$id]) || isset($this->protected[$id])) {
            throw new \RuntimeException(sprintf('Cannot override frozen service "%s".', $id));
        }

        $this->keys[$id] = true;

        if (!is_object($value) || !method_exists($value, '__invoke')) {
            $this->protected[$id] = $value;
        } else {
            $this->values[$id] = $value;
        }
    }

    /**
     * Retrieves the service object or protected value from the Application container.
     *
     * If the service has not already been instantiated, the Closure to create
     * it will be executed and the result saved to the Application object.
     * If the service has already been instantiated, the previous version will be retrieved
     * from the Application container. Non-Closures will be returned directly.
     *
     * @param string $id
     * @return object
     */
    public function offsetGet($id)
    {
        if (!isset($this->keys[$id])) {
            throw new \InvalidArgumentException(sprintf('Identifier "%s" is not defined.', $id));
        }

        if (isset($this->raw[$id])) {
            return $this->values[$id];
        }

        if (isset($this->protected[$id])) {
            return $this->protected[$id];
        }

        $raw = $this->values[$id];
        $this->values[$id] = $raw($this);
        $this->raw[$id] = $raw;
        $this->frozen[$id] = true;

        return $this->values[$id];
    }

    /**
     * Checks if a parameter or an object is set.
     *
     * @param string $id
     * @return bool
     */
    public function offsetExists($id)
    {
        return isset($this->keys[$id]);
    }

    /**
     * Unsets a parameter or an object.
     *
     * @param string $id The unique identifier for the parameter or object
     */
    public function offsetUnset($id)
    {
        if (isset($this->keys[$id])) {
            unset($this->values[$id], $this->frozen[$id], $this->raw[$id], $this->keys[$id]);
        }
    }

    /**
     * Sets the object properties to prepare for the loop.
     */
    public function rewind()
    {
        $this->position = 0;
        $this->iterableKeys = array_keys($this->values);
    }

    /**
     * Retrieves the service object for the current step in the loop.
     *
     * @return object
     */
    public function current()
    {
        return $this[$this->iterableKeys[$this->position]];
    }

    /**
     * Retrieves the key for the current step in the loop.
     *
     * @return string
     */
    public function key()
    {
        return $this->iterableKeys[$this->position];
    }

    /**
     * Increments to the next step in the loop.
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * Checks if the next step in the loop in valid.
     *
     * @return bool
     */
    public function valid()
    {
        return isset($this->iterableKeys[$this->position]);
    }
}
