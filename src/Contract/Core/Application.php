<?php
namespace Intraxia\Jaxion\Contract\Core;

use Closure;

interface Application
{
    /**
     * Instantiates a new Application container.
     *
     * The Application constructor enforces the presence of of a single instance
     * of the Application. If an instance already exists, an Exception will be thrown.
     *
     * @param string $file
     * @throws \Intraxia\Jaxion\Core\ApplicationAlreadyBootedException
     */
    public function __construct($file);

    /**
     * Starts up the Application.
     *
     * Retrieves the Application's loader instance, and runs the Loader's register method,
     * attaching all of the Application container's services to their respective WordPress hooks.
     */
    public function boot();

    /**
     * Fired on plugin activation.
     *
     * This function is attached to `register_activation_hook` and is fired when the plugin is
     * activated by WordPress. This gives the developer a place to set up any options,
     * add any custom tables, or flush rewrite rules, as required.
     */
    public function activate();

    /**
     * Fired on plugin deactivation.
     *
     * This function is attached to `register_deactivation_hook` and is fired when the plugin
     * is deactivated by WordPress. This gives the developer a place to clean up anything left
     * behind by the plugin.
     */
    public function deactivate();

    /**
     * Registers a command with WP-CLI.
     *
     * This is a helper function for registering commands. The first parameter is the command name
     * as you would normally register with WP-CLI. The second parameter should be a closure that
     * returns the class to register with WP-CLI. This cleans up some of the boilerplate required
     * to register commands as well as make WP-CLI command classes injectable.
     *
     * @param string $name
     * @param Closure $class
     */
    public function command($name, Closure $class);

    /**
     * Retrieves the booted Application.
     *
     * If the Application has not yet been booted, an Exception will be thrown.
     *
     * @return Application
     * @throws \Intraxia\Jaxion\Core\ApplicationNotBootedException
     */
    public static function get();

    /**
     * Shuts down the booted Application.
     *
     * If the Application has already been booted, the Application instance
     * will be destroyed by assigning it a null value, freeing it from memory.
     * However, the service objects will likely remain in memory if they've been
     * attached to hooks when this method is called. This function is primarily
     * for uniting testing to make sure you can boot a new instance for each test.
     */
    public static function shutdown();
}
