<?php
namespace Intraxia\Jaxion\Test;

use Intraxia\Jaxion\Core\Container;
use Mockery;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Container
     */
    public $container;

    public function setUp()
    {
        parent::setUp();
        $this->container = new Container();
    }

    public function testStringsShouldBeProtected()
    {
        $this->container['key'] = 'test';

        $this->assertEquals('test', $this->container['key']);

        $this->setExpectedException('RuntimeException');

        $this->container['key'] = 'newstring';
    }

    public function testIntegersShouldBeProtected()
    {
        $this->container['key'] = 123;

        $this->assertEquals($this->container['key'], 123);

        $this->setExpectedException('RuntimeException');

        $this->container['key'] = 456;
    }

    public function testBooleansShouldBeProtected()
    {
        $this->container['key'] = true;

        $this->assertEquals($this->container['key'], true);

        $this->setExpectedException('RuntimeException');

        $this->container['key'] = false;
    }

    public function testShouldNotAcceptNull()
    {
        $this->setExpectedException('RuntimeException');

        $this->container['key'] = null;
    }

    public function testArraysShouldBeProtected()
    {
        $this->container['key'] = array('one');

        $this->assertEquals(array('one'), $this->container['key']);

        $this->setExpectedException('RuntimeException');

        $this->container['key'] = array('two');
    }

    public function testObjectsShouldBeProtected()
    {
        $this->container['key'] = new \stdClass();

        $this->assertInstanceOf('stdClass', $this->container['key']);

        $this->setExpectedException('RuntimeException');

        $this->container['key'] = new \stdClass;
    }

    public function testShouldImplementArrayAccess()
    {
        $this->container['key'] = function () {
            return 'value';
        };

        $this->assertEquals($this->container['key'], 'value');
        $this->assertTrue(isset($this->container['key']));

        unset($this->container['key']);

        $this->assertFalse(isset($this->container['key']));
    }

    public function testShouldImplementIterator()
    {
        $looped = false;

        $this->container['key'] = function () {
            return 'value';
        };

        foreach ($this->container as $key => $value) {
            $this->assertEquals('key', $key);
            $this->assertEquals('value', $value);
            $looped = true;
        }

        $this->assertTrue($looped, 'Application did not enter the loop.');
    }

    public function testShouldFailGettingUnsetKeys()
    {


        $this->setExpectedException('InvalidArgumentException');

        $this->container['test'];
    }

    public function testShouldTNotOverwriteGeneratedServices()
    {
        $this->container['test'] = function () {
            return new \stdClass();
        };

        $this->container['test'];

        $this->setExpectedException('RuntimeException');

        $this->container['test'] = function () {
            return new \stdClass();
        };
    }

    public function testShouldReturnAlreadyGeneratedService()
    {
        $this->container['test'] = function () {
            return new \stdClass();
        };

        $service1 = $this->container['test'];
        $service2 = $this->container['test'];

        $this->assertSame($service1, $service2);
    }
}
