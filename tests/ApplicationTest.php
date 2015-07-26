<?php
namespace Intraxia\Jaxion\Test;

use Intraxia\Jaxion\Core\Application as App;
use Mockery;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{

    public function testShouldThrowExceptionIfNotBooted()
    {
        $this->setExpectedException('Intraxia\Jaxion\Core\ApplicationNotBootedException');
        App::get();
    }

    public function testShouldGetInstantiatedInstance()
    {
        $app1 = new App();
        $app2 = App::get();

        $this->assertSame($app1, $app2);
    }

    public function testShouldThrowExceptionIfAlreadyBooted()
    {
        new App();

        $this->setExpectedException('Intraxia\Jaxion\Core\ApplicationAlreadyBootedException');

        new App();
    }

    public function testShouldShutdown()
    {
        new App();
        App::shutdown();

        $this->setExpectedException('Intraxia\Jaxion\Core\ApplicationNotBootedException');
        App::get();
    }

    public function testShouldHaveLoader()
    {
        $app = new App();

        $this->assertInstanceOf('Intraxia\Jaxion\Core\Loader', $app['Loader']);
    }

    public function testShouldRunLoaderRegister()
    {
        $app = new App();

        $app['Loader'] = function() {
            $loader = Mockery::mock('Intraxia\Jaxion\Loader')->shouldDeferMissing();
            $loader->shouldReceive('register')
                ->once();

            return $loader;
        };

        $app->boot();
    }

    public function tearDown()
    {
        parent::tearDown();
        App::shutdown();
        Mockery::close();
    }
}
