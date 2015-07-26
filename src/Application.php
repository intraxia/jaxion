<?php namespace Intraxia\Jaxion;

use Intraxia\Jaxion\Exceptions\ApplicationAlreadyBootedException;
use Intraxia\Jaxion\Exceptions\ApplicationNotBootedException;

/**
 * Class Application
 * @package Intraxia\Jaxion
 */
class Application implements \ArrayAccess, \Iterator{

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
     * Singleton instance of the Application object
     *
     * @var Application
     */
    protected static $instance = null;

    /**
     * Instantiates a new Application container.
     *
     * The Application constructor enforces the
     *
     * @throws ApplicationAlreadyBootedException
     */
    public function __construct()
    {
        if (static::$instance !== null) {
            throw new ApplicationAlreadyBootedException;
        }

        static::$instance = $this;

        $this['Loader'] = function($app) {
            return new Loader($app);
        };
    }

    /**
     * Starts up up the Application.
     */
    public function boot()
    {
        $this['Loader']->register();
    }

    /**
     * Set an object into the Application container.
     *
     * Values must be a Closure that returns the service you want to attach to the
     * Application. An Exception will be thrown if the value is not a Closure.
     *
     * @param  string $id
     * @param  \Closure $value
     * @throws \RuntimeException
     */
    public function offsetSet($id, $value)
    {
        if (isset($this->frozen[$id])) {
            throw new \RuntimeException(sprintf('Cannot override frozen service "%s".', $id));
        }

        if (!method_exists($value, '__invoke')) {
            throw new \RuntimeException(sprintf('Service "%s" is not a closure.', $id));
        }

        $this->values[$id] = $value;
        $this->keys[$id] = true;
    }

    /**
     * Retrieves the service object from the Application container.
     *
     * If the service has not already been instantiated, the Closure to create
     * it will be executed and the result saved to the Application object.
     * If the service has already been instantiate, the previous version will be retrieved
     * from the Application container.
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

    /**
     * Retrieve the booted Application.
     *
     * If the Application has not yet been booted, an Exception will be thrown.
     *
     * @return Application
     * @throws ApplicationNotBootedException
     */
    public static function get()
    {
        if (static::$instance === null) {
            throw new ApplicationNotBootedException;
        }

        return static::$instance;
    }

    /**
     * Shuts down the booted Application.
     *
     * If the Application has already been booted, the Application instance
     * will be destroyed by assigning it a null value, freeing it from memory.
     * However, the service objects will likely remain in memory if they've been
     * attached to hooks when this method is called. This function is primarily
     * for uniting testing to make sure you can boot a new instance for each test.
     */
    public static function shutdown()
    {
        if (static::$instance !== null) {
            static::$instance = null;
        }
    }
}
