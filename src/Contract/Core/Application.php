<?php
namespace Intraxia\Jaxion\Contract\Core;

interface Application
{
    /**
     * Starts up the Application.
     */
    public function boot();

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
