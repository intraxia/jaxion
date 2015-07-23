<?php
namespace Intraxia\Jaxion\Test;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{

    public function testShouldThrowExceptionIfNotBooted()
    {
        $this->setExpectedException('Intraxia\Jaxion\Exceptions\ApplicationNotBootedException');
        App::get();
    }

    public function testShouldBoot()
    {
        App::boot();

        $app = App::get();
        $this->assertInstanceOf('Intraxia\Jaxion\Application', $app);
    }

    public function testShouldShutdown()
    {
        App::boot();
        App::shutdown();

        $this->setExpectedException('Intraxia\Jaxion\Exceptions\ApplicationNotBootedException');
        App::get();
    }

    public function testShouldNotAcceptStrings()
    {
        App::boot();

        $app = App::get();
        $this->setExpectedException('RuntimeException');
        $app['key'] = 'test';
    }

    public function testShouldNotAcceptIntegers()
    {
        App::boot();

        $app = App::get();
        $this->setExpectedException('RuntimeException');
        $app['key'] = 123;
    }

    public function testShouldNotAcceptBooleans()
    {
        App::boot();

        $app = App::get();
        $this->setExpectedException('RuntimeException');
        $app['key'] = true;
    }

    public function testShouldNotAcceptNull()
    {
        App::boot();

        $app = App::get();
        $this->setExpectedException('RuntimeException');
        $app['key'] = null;
    }

    public function testShouldNotAcceptArrays()
    {
        App::boot();

        $app = App::get();
        $this->setExpectedException('RuntimeException');
        $app['key'] = array();
    }

    public function testShouldNotAcceptObjects()
    {
        App::boot();

        $app = App::get();
        $this->setExpectedException('RuntimeException');
        $app['key'] = new \stdClass;
    }

    public function testShouldImplementInterfaces()
    {
        $looped = false;
        App::boot();
        $app = App::get();

        $app['key'] = function() {
            return 'value';
        };

        foreach ($app as $key => $value) {
            $this->assertEquals('key', $key);
            $this->assertEquals('value', $value);
            $looped = true;
        }

        $this->assertTrue($looped, 'Application did not enter the loop.');
    }

    public function testShouldFailGettingUnsetKeys()
    {
        App::boot();
        $app = App::get();

        $this->setExpectedException('InvalidArgumentException');

        $app['test'];
    }

    public function testShouldTNotOverwriteGeneratedServices()
    {
        App::boot();
        $app = App::get();

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
        App::boot();
        $app = App::get();

        $app['test'] = function() {
            return new \stdClass();
        };

        $service1 = $app['test'];
        $service2 = $app['test'];

        $this->assertEquals($service1, $service2);
    }

    public function tearDown()
    {
        App::shutdown();
    }
}

/**
 * Class App
 *
 * Application stub to test the Application container
 * without invoking the Loader construction.
 *
 * @package Intraxia\Jaxion\Test
 */
class App extends \Intraxia\Jaxion\Application
{
    protected function startup() {}
}
