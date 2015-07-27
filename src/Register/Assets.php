<?php
namespace Intraxia\Jaxion\Register;

use Intraxia\Jaxion\Contract\Register\Assets as AssetsContract;

class Assets implements AssetsContract
{
    /**
     * Minification string for enqueued assets.
     *
     * @var string
     */
    private $min = '';

    /**
     * Url to the plugin directory.
     *
     * @var string
     */
    protected $url;

    /**
     * Registration hooks for all assets.
     *
     * @var array
     */
    public $actions = array(
        array(
            'hook' => 'wp_enqueue_scripts',
            'method' => 'enqueueWebScripts',
        ),
        array(
            'hook' => 'wp_enqueue_scripts',
            'method' => 'enqueueWebStyles',
        ),
        array(
            'hook' => 'admin_enqueue_scripts',
            'method' => 'enqueueAdminScripts',
        ),
        array(
            'hook' => 'admin_enqueue_scripts',
            'method' => 'enqueueAdminStyles',
        ),
    );

    /**
     * Array of script definition arrays.
     *
     * @var array
     */
    public $scripts = array();

    /**
     * Array of style definition arrays.
     *
     * @var array
     */
    public $styles = array();

    /**
     * @inheritDoc
     */
    public function __construct($url)
    {
        $this->url = $url; // @todo should we trailingslashit this?
    }

    /**
     * @inheritdoc
     */
    public function setDebug($debug)
    {
        if ($debug) {
            $this->min = '.min';
        } else {
            $this->min = '';
        }
    }

    /**
     * @inheritDoc
     */
    public function enqueueWebScripts()
    {
        foreach ($this->scripts as $script) {
            if (in_array($script['type'], array('web', 'shared'))) {
                $this->enqueueScript($script);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function enqueueWebStyles()
    {
        foreach ($this->styles as $style) {
            if (in_array($style['type'], array('web', 'shared'))) {
                $this->enqueueStyle($style);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function enqueueAdminScripts()
    {
        foreach ($this->scripts as $script) {
            if (in_array($script['type'], array('admin', 'shared'))) {
                $this->enqueueScript($script);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function enqueueAdminStyles()
    {
        foreach ($this->styles as $style) {
            if (in_array($style['type'], array('admin', 'shared'))) {
                $this->enqueueStyle($style);
            }
        }
    }

    /**
     * Enqueues an individual script if the style's condition is met.
     *
     * @param array $script
     */
    protected function enqueueScript($script)
    {
        if ($script['condition']()) {
            wp_enqueue_script(
                $script['handle'],
                $this->url . $script['src'],
                isset($script['deps']) ? $script['deps'] : array(),
                null, // @todo implement version
                isset($script['footer']) ? $script['footer'] : false
            );
        }
    }

    /**
     * Enqueues an individual stylesheet if the style's condition is met.
     *
     * @param array $style
     */
    protected function enqueueStyle($style)
    {
        if ($style['condition']()) {
            wp_enqueue_style(
                $style['handle'],
                $this->url . $style['src'],
                isset($style['deps']) ? $style['deps'] : array(),
                null, // @todo implement version
                isset($style['media']) ? $style['media'] : 'all'
            );
        }
    }
}
