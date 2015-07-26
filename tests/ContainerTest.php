<?php
namespace Intraxia\Jaxion\Test;

use Intraxia\Jaxion\Core\Container;
use Mockery;

class ContainerTest extends \PHPUnit_Framework_TestCase
{

    public function testShouldNotAcceptStrings()
    {
        $container = new Container();

        $this->setExpectedException('RuntimeException');

        $container['key'] = 'test';
    }

    public function testShouldNotAcceptIntegers()
    {
        $container = new Container();

        $this->setExpectedException('RuntimeException');

        $container['key'] = 123;
    }

    public function testShouldNotAcceptBooleans()
    {
        $container = new Container();

        $this->setExpectedException('RuntimeException');

        $container['key'] = true;
    }

    public function testShouldNotAcceptNull()
    {
        $container = new Container();

        $this->setExpectedException('RuntimeException');

        $container['key'] = null;
    }

    public function testShouldNotAcceptArrays()
    {
        $container = new Container();

        $this->setExpectedException('RuntimeException');

        $container['key'] = array();
    }

    public function testShouldNotAcceptObjects()
    {
        $container = new Container();

        $this->setExpectedException('RuntimeException');

        $container['key'] = new \stdClass;
    }

    public function testShouldImplementArrayAccess()
    {
        $container = new Container();

        $container['key'] = function() {
            return 'value';
        };

        $this->assertEquals($container['key'], 'value');
        $this->assertTrue(isset($container['key']));

        unset($container['key']);

        $this->assertFalse(isset($container['key']));
    }

    public function testShouldImplementIterator()
    {
        $looped = false;
        $container = new Container();
        $container['key'] = function() {
            return 'value';
        };

        foreach ($container as $key => $value) {
            $this->assertEquals('key', $key);
            $this->assertEquals('value', $value);
            $looped = true;
        }

        $this->assertTrue($looped, 'Application did not enter the loop.');
    }

    public function testShouldFailGettingUnsetKeys()
    {
        $container = new Container();

        $this->setExpectedException('InvalidArgumentException');

        $container['test'];
    }

    public function testShouldTNotOverwriteGeneratedServices()
    {
        $container = new Container();

        $container['test'] = function() {
            return new \stdClass();
        };

        $container['test'];

        $this->setExpectedException('RuntimeException');

        $container['test'] = function() {
            return new \stdClass();
        };
    }

    public function testShouldReturnAlreadyGeneratedService()
    {
        $container = new Container();

        $container['test'] = function() {
            return new \stdClass();
        };

        $service1 = $container['test'];
        $service2 = $container['test'];

        $this->assertSame($service1, $service2);
    }
}
