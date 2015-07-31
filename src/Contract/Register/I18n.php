<?php
namespace Intraxia\Jaxion\Contract\Register;

interface I18n
{
    /**
     * Instantiates the Internationalization object.
     *
     * The path variable should be the Application's path value,
     * which points to the directory the plugin currently resides in.
     *
     * @param string $path
     */
    public function __construct($path);

    /**
     * Loads the plugin textdomain translation.
     *
     * This method is fired on the `after_theme_setup` hook to ensure
     * it is fired at all. The Loader runs on the `plugins_loaded` hook,
     * so attaching this method to the `plugins_loaded` hook may be too
     * late to make sure this function is fired appropriately.
     */
    public function loadTranslation();
}
