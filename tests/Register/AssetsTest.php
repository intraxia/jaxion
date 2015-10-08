<?php
namespace Intraxia\Jaxion\Test\Register;

use WP_Mock;
use Intraxia\Jaxion\Register\Assets;
use ReflectionProperty;

class AssetsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Assets
     */
    public $assets;

    public function setUp()
    {
        parent::setUp();
        WP_Mock::setUp();
        $this->assets = new Assets('test.com/');
    }

    public function testShouldToggleDebugMode()
    {
        $min = new ReflectionProperty($this->assets, 'min');
        $min->setAccessible(true);

        $this->assets->setDebug(true);

        $this->assertEquals('.min', $min->getValue($this->assets));

        $this->assets->setDebug(false);

        $this->assertEquals('', $min->getValue($this->assets));
    }

    public function testShouldEnqueueWebScript()
    {
        $this->assets->registerScript(array(
            'type' => 'web',
            'condition' => function () {
                return true;
            },
            'handle' => 'webScript',
            'src' => 'test',
        ));

        WP_Mock::wpFunction('wp_enqueue_script', array(
            'times' => 1,
            'args' => array( 'webScript', 'test.com/test.js', array(), null, false ),
        ));

        $this->assets->enqueueWebScripts();
    }

    public function testShouldNotEnqueueWebScriptIfFalseCondition()
    {
        $this->assets->registerScript(array(
            'type' => 'web',
            'condition' => function () {
                return false;
            },
            'handle' => 'webScript',
            'src' => 'test',
        ));

        WP_Mock::wpFunction('wp_enqueue_script', array(
            'times' => 0,
        ));

        $this->assets->enqueueWebScripts();
    }

    public function testShouldEnqueueWebStyle()
    {
        $this->assets->registerStyle(array(
            'type' => 'web',
            'condition' => function () {
                return true;
            },
            'handle' => 'webStyle',
            'src' => 'test',
        ));

        WP_Mock::wpFunction('wp_enqueue_style', array(
            'times' => 1,
            'args' => array( 'webStyle', 'test.com/test.css', array(), null, 'all' ),
        ));

        $this->assets->enqueueWebStyles();
    }

    public function testShouldNotEnqueueWebStyleIfFalseCondition()
    {
        $this->assets->registerStyle(array(
            'type' => 'web',
            'condition' => function () {
                return false;
            },
            'handle' => 'webStyle',
            'src' => 'test',
        ));

        WP_Mock::wpFunction('wp_enqueue_style', array(
            'times' => 0,
        ));

        $this->assets->enqueueWebStyles();
    }

    public function testShouldEnqueueAdminScript()
    {
        $this->assets->registerScript(array(
            'type' => 'admin',
            'condition' => function () {
                return true;
            },
            'handle' => 'adminScript',
            'src' => 'test',
        ));

        WP_Mock::wpFunction('wp_enqueue_script', array(
            'times' => 1,
            'args' => array( 'adminScript', 'test.com/test.js', array(), null, false ),
        ));

        $this->assets->enqueueAdminScripts();
    }

    public function testShouldNotEnqueueAdminScriptIfFalseCondition()
    {
        $this->assets->registerScript(array(
            'type' => 'admin',
            'condition' => function () {
                return false;
            },
            'handle' => 'adminScript',
            'src' => 'test',
        ));

        WP_Mock::wpFunction('wp_enqueue_script', array(
            'times' => 0,
        ));

        $this->assets->enqueueAdminScripts();
    }

    public function testShouldEnqueueAdminStyle()
    {
        $this->assets->registerStyle(array(
            'type' => 'admin',
            'condition' => function () {
                return true;
            },
            'handle' => 'adminStyle',
            'src' => 'test',
        ));

        WP_Mock::wpFunction('wp_enqueue_style', array(
            'times' => 1,
            'args' => array( 'adminStyle', 'test.com/test.css', array(), null, 'all' ),
        ));

        $this->assets->enqueueAdminStyles();
    }

    public function testShouldNotEnqueueAdminStyleIfFalseCondition()
    {
        $this->assets->registerStyle(array(
            'type' => 'admin',
            'condition' => function () {
                return false;
            },
            'handle' => 'adminStyle',
            'src' => 'test',
        ));

        WP_Mock::wpFunction('wp_enqueue_style', array(
            'times' => 0,
        ));

        $this->assets->enqueueAdminStyles();
    }

    public function testShouldEnqueueSharedScript()
    {
        $this->assets->registerScript(array(
            'type' => 'shared',
            'condition' => function () {
                return true;
            },
            'handle' => 'sharedScript',
            'src' => 'test',
        ));

        WP_Mock::wpFunction('wp_enqueue_script', array(
            'times' => 2,
            'args' => array( 'sharedScript', 'test.com/test.js', array(), null, false ),
        ));

        $this->assets->enqueueWebScripts();
        $this->assets->enqueueAdminScripts();
    }

    public function testShouldNotEnqueueSharedScriptIfFalseCondition()
    {
        $this->assets->registerScript(array(
            'type' => 'shared',
            'condition' => function () {
                return false;
            },
            'handle' => 'sharedScript',
            'src' => 'test',
        ));

        WP_Mock::wpFunction('wp_enqueue_script', array(
            'times' => 0,
        ));

        $this->assets->enqueueWebScripts();
        $this->assets->enqueueAdminScripts();
    }

    public function testShouldEnqueueSharedStyle()
    {
        $this->assets->registerStyle(array(
            'type' => 'shared',
            'condition' => function () {
                return true;
            },
            'handle' => 'sharedStyle',
            'src' => 'test',
        ));

        WP_Mock::wpFunction('wp_enqueue_style', array(
            'times' => 2,
            'args' => array( 'sharedStyle', 'test.com/test.css', array(), null, 'all' ),
        ));

        $this->assets->enqueueWebStyles();
        $this->assets->enqueueAdminStyles();
    }

    public function testShouldNotEnqueueSharedStyleIfFalseCondition()
    {
        $this->assets->registerStyle(array(
            'type' => 'shared',
            'condition' => function () {
                return false;
            },
            'handle' => 'sharedStyle',
            'src' => 'test',
        ));

        WP_Mock::wpFunction('wp_enqueue_style', array(
            'times' => 0,
        ));

        $this->assets->enqueueWebStyles();
        $this->assets->enqueueAdminStyles();
    }

    public function testShouldLocalizeScriptIfSet()
    {
        $this->assets->registerScript(array(
            'type' => 'web',
            'condition' => function () {
                return true;
            },
            'handle' => 'webScript',
            'src' => 'test',
            'localize' => array(
                'name' => 'test_local',
                'data' => 'local_test',
            )
        ));

        WP_Mock::wpFunction('wp_enqueue_script', array(
            'times' => 1,
            'args' => array( 'webScript', 'test.com/test.js', array(), null, false ),
        ));

        WP_Mock::wpFunction('wp_localize_script', array(
            'times' => 1,
            'args' => array('webScript', 'test_local', 'local_test'),
        ));

        $this->assets->enqueueWebScripts();
    }

    public function tearDown()
    {
        parent::tearDown();
        WP_Mock::tearDown();
    }
}
