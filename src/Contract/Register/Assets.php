<?php
namespace Intraxia\Jaxion\Contract\Register;

interface Assets
{
    /**
     * Instantiates a new instance of the Assets class.
     *
     * The URL param should be relative to the plugin directory. The URL
     * form should always end with a '/'. All asset location definitions
     * should not begin with a slash and should be relative to the plugin's
     * root directory.
     *
     * @param string $url
     */
    public function __construct($url);

    /**
     * Enable debug mode for the enqueued assets.
     *
     * Debug mode is not required and can be enabled/disabled based on whatever
     * runtime required by the developer. Primarily, this is intended to be used
     * along with WordPress's `SCRIPT_DEBUG` constant, which enables unminified
     * core assets to be enqueued.
     *
     * @param bool $debug
     */
    public function setDebug($debug);

    /**
     * Loops through the Assets' `$scripts` property and enqueues the Web + Shared scripts.
     */
    public function enqueueWebScripts();

    /**
     * Loops through the Assets' `$styles` property and enqueues the Web + Shared styles.
     */
    public function enqueueWebStyles();

    /**
     * Loops through the Assets' `$scripts` property and enqueues the Admin + Shared scripts.
     */
    public function enqueueAdminScripts();

    /**
     * Loops through the Assets' `$styles` property and enqueues the Admin + Shared styles.
     */
    public function enqueueAdminStyles();
}
