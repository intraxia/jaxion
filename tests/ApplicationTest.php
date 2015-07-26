<?php
namespace Intraxia\Jaxion\Test;

use Intraxia\Jaxion\Application as App;
use Mockery;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{

    public function testShouldThrowExceptionIfNotBooted()
    {
        $this->setExpectedException('Intraxia\Jaxion\Exceptions\ApplicationNotBootedException');
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

        $this->setExpectedException('Intraxia\Jaxion\Exceptions\ApplicationAlreadyBootedException');

        new App();
    }

    public function testShouldShutdown()
    {
        new App();
        App::shutdown();

        $this->setExpectedException('Intraxia\Jaxion\Exceptions\ApplicationNotBootedException');
        App::get();
    }

    public function testShouldNotAcceptStrings()
    {
        $app = new App();

        $this->setExpectedException('RuntimeException');

        $app['key'] = 'test';
    }

    public function testShouldNotAcceptIntegers()
    {
        $app = new App();

        $this->setExpectedException('RuntimeException');

        $app['key'] = 123;
    }

    public function testShouldNotAcceptBooleans()
    {
        $app = new App();

        $this->setExpectedException('RuntimeException');

        $app['key'] = true;
    }

    public function testShouldNotAcceptNull()
    {
        $app = new App();

        $this->setExpectedException('RuntimeException');

        $app['key'] = null;
    }

    public function testShouldNotAcceptArrays()
    {
        $app = new App();

        $this->setExpectedException('RuntimeException');

        $app['key'] = array();
    }

    public function testShouldNotAcceptObjects()
    {
        $app = new App();

        $this->setExpectedException('RuntimeException');

        $app['key'] = new \stdClass;
    }

    public function testShouldImplementArrayAccess()
    {
        $app = new App();

        $app['key'] = function() {
            return 'value';
        };

        $this->assertEquals($app['key'], 'value');
        $this->assertTrue(isset($app['key']));

        unset($app['key']);

        $this->assertFalse(isset($app['key']));
    }

    public function testShouldImplementIterator()
    {
        $looped = false;
        $app = new App();

        foreach ($app as $key => $value) {
            $this->assertEquals('Loader', $key);
            $this->assertInstanceOf('Intraxia\Jaxion\Loader', $value);
            $looped = true;
        }

        $this->assertTrue($looped, 'Application did not enter the loop.');
    }

    public function testShouldFailGettingUnsetKeys()
    {
        $app = new App();

        $this->setExpectedException('InvalidArgumentException');

        $app['test'];
    }

    public function testShouldTNotOverwriteGeneratedServices()
    {
        $app = new App();

        $app['test'] = function() {
            return new \stdClass();
        };

        $app['test'];

        $this->setExpectedException('RuntimeException');

        $app['test'] = function() {
            return new \stdClass();
        };
    }

    public function testShouldReturnAlreadyGeneratedService()
    {
        $app = new App();

        $app['test'] = function() {
            return new \stdClass();
        };

        $service1 = $app['test'];
        $service2 = $app['test'];

        $this->assertSame($service1, $service2);
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
    }
}
