<?php namespace Intraxia\Jaxion\Core;

use Intraxia\Jaxion\Contract\Core\Application as ApplicationContract;
use Intraxia\Jaxion\Register\I18n;

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
     * @inheritdoc
     */
    public function __construct($file)
    {
        if (static::$instance !== null) {
            throw new ApplicationAlreadyBootedException;
        }

        static::$instance = $this;

        $this['url'] = plugin_dir_url($file);
        $this['path'] = plugin_dir_path($file);
        $this['basename'] = plugin_basename($file);

        $this['I18n'] = function ($app) {
            return new I18n($app['path']);
        };

        $this['Loader'] = function ($app) {
            return new Loader($app);
        };

        register_activation_hook($file, array($this, 'activate'));
        register_deactivation_hook($file, array($this, 'deactivate'));
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        $this['Loader']->register();
    }

    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function activate()
    {
        // no-op
    }

    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function deactivate()
    {
        // no-op
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
