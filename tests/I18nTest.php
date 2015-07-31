<?php
namespace Intraxia\Jaxion\Test;

use Intraxia\Jaxion\Register\I18n;
use WP_Mock;

class I18nTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var I18n
     */
    public $i18n;

    public function setUp()
    {
        parent::setUp();
        WP_Mock::setUp();
        $this->i18n = new I18n(__DIR__);
    }

    public function testShouldLoadTextDomain()
    {
        WP_Mock::wpFunction('load_plugin_textdomain', array(
            'times' => 1,
            'args' => array('tests', false, 'tests/languages/'),
        ));

        $this->i18n->loadTranslation();
    }

    public function tearDown()
    {
        parent::tearDown();
        WP_Mock::tearDown();
    }
}
