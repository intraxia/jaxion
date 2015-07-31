<?php
namespace Intraxia\Jaxion\Test;

use Intraxia\Jaxion\Core\Application as App;
use Mockery;
use WP_Mock;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
        WP_Mock::setUp();
    }

    public function testShouldThrowExceptionIfNotBooted()
    {
        $this->setExpectedException('Intraxia\Jaxion\Core\ApplicationNotBootedException');

        App::get();
    }

    public function testShouldGetInstantiatedInstance()
    {
        $this->mockConstructorFunctions();

        $app1 = new App(__FILE__);
        $app2 = App::get();

        $this->assertSame($app1, $app2);
    }

    public function testShouldThrowExceptionIfAlreadyBooted()
    {
        $this->mockConstructorFunctions();

        new App(__FILE__);

        $this->setExpectedException('Intraxia\Jaxion\Core\ApplicationAlreadyBootedException');

        new App(__FILE__);
    }

    public function testShouldShutdown()
    {
        $this->mockConstructorFunctions();

        new App(__FILE__);
        App::shutdown();

        $this->setExpectedException('Intraxia\Jaxion\Core\ApplicationNotBootedException');
        App::get();
    }

    public function testShouldHaveLoader()
    {
        $this->mockConstructorFunctions();

        $app = new App(__FILE__);

        $this->assertInstanceOf('Intraxia\Jaxion\Core\Loader', $app['Loader']);
    }

    public function testShouldHaveSettings()
    {
        $this->mockConstructorFunctions();

        $app = new App(__FILE__);

        $this->assertTrue(isset($app['url']));
        $this->assertTrue(isset($app['path']));
        $this->assertTrue(isset($app['basename']));
    }

    public function testShouldRunLoaderRegister()
    {
        $this->mockConstructorFunctions();

        $app = new App(__FILE__);

        $app['Loader'] = function () {
            $loader = Mockery::mock('Intraxia\Jaxion\Loader')->shouldDeferMissing();
            $loader->shouldReceive('register')
                ->once();

            return $loader;
        };

        $app->boot();
    }

    public function testShouldHaveI18n()
    {
        $this->mockConstructorFunctions();

        $app = new App(__FILE__);

        $this->assertInstanceOf('Intraxia\Jaxion\Register\I18n', $app['I18n']);
    }

    protected function mockConstructorFunctions()
    {
        WP_Mock::wpPassthruFunction('plugin_dir_url', array('times' => 1));
        WP_Mock::wpPassthruFunction('plugin_dir_path', array('times' => 1));
        WP_Mock::wpPassthruFunction('plugin_basename', array('times' => 1));
    }

    public function tearDown()
    {
        parent::tearDown();
        App::shutdown();
        Mockery::close();
        WP_Mock::tearDown();
    }
}
