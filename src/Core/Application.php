<?php namespace Intraxia\Jaxion\Core;

use Intraxia\Jaxion\Contract\Core\Application as ApplicationContract;

/**
 * Class Application
 * @package Intraxia\Jaxion
 */
class Application extends Container implements ApplicationContract
{
    /**
     * Singleton instance of the Application object
     *
     * @var Application
     */
    protected static $instance = null;

    /**
     * Instantiates a new Application container.
     *
     * The Application constructor enforces the presence of of a single instance
     * of the Application. If an instance already exists, an Exception will be thrown.
     *
     * @throws ApplicationAlreadyBootedException
     */
    public function __construct()
    {
        if (static::$instance !== null) {
            throw new ApplicationAlreadyBootedException;
        }

        static::$instance = $this;

        $this['Loader'] = function ($app) {
            return new Loader($app);
        };
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        $this['Loader']->register();
    }

    /**
     * @inheritDoc
     */
    public static function get()
    {
        if (static::$instance === null) {
            throw new ApplicationNotBootedException;
        }

        return static::$instance;
    }

    /**
     * @inheritDoc
     */
    public static function shutdown()
    {
        if (static::$instance !== null) {
            static::$instance = null;
        }
    }
}
