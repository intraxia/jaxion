<?php
namespace Intraxia\Jaxion\Test\Core;

use Intraxia\Jaxion\Core\Container;

class ContainerTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var Container
	 */
	public $container;

	public function setUp() {
		parent::setUp();
		$this->container = new Container();
	}

	public function test_should_fetch_defined_value() {
		$this->container->define( 'key', 'value' );

		$this->assertSame( 'value', $this->container->fetch( 'key' ) );
	}

	public function test_should_not_overwrite_defined_value() {
		$this->container->define( 'key', 'value' );

		$this->setExpectedException( 'Intraxia\Jaxion\Core\DefinedAliasException' );

		$this->container->define( 'key', 'value' );
	}

	public function test_should_always_execute_defined_closure() {
		$this->container->define( 'key', function () {
			return new \stdClass;
		} );

		$this->assertNotSame( $this->container->fetch( 'key' ), $this->container->fetch( 'key' ) );
	}

	public function test_should_return_shared_object() {
		$this->container->share( 'key', function () {
			return new \stdClass;
		} );

		$this->assertSame( $this->container->fetch( 'key' ), $this->container->fetch( 'key' ) );
	}

	public function test_should_accept_defined_alias() {
		$this->container->define( array( 'alias' => 'stdClass' ), function () {
			return new \stdClass;
		} );

		$this->assertInstanceOf( 'stdClass', $this->container->fetch( 'alias' ) );
		$this->assertInstanceOf( 'stdClass', $this->container->fetch( 'stdClass' ) );
	}

	public function test_should_accept_shared_alias() {
		$this->container->share( array( 'alias' => 'stdClass' ), function () {
			return new \stdClass;
		} );

		$this->assertInstanceOf( 'stdClass', $this->container->fetch( 'alias' ) );
		$this->assertInstanceOf( 'stdClass', $this->container->fetch( 'stdClass' ) );
	}

	public function test_should_not_fetch_undefined_aliases() {
		$this->setExpectedException( 'Intraxia\Jaxion\Core\UndefinedAliasException' );

		$this->container->fetch( 'key' );
	}

	public function test_should_have_defined_key() {
		$this->container->define( 'key', function () {
			return 'value';
		} );

		$this->assertTrue( $this->container->has( 'key' ) );
	}

	public function test_should_have_shared_key() {
		$this->container->define( 'key', function () {
			return 'value';
		} );

		$this->assertTrue( $this->container->has( 'key' ) );
	}

	public function test_should_remove_defined_key() {
		$this->container->define( 'key', function () {
			return 'value';
		} );

		$this->container->remove( 'key' );

		$this->assertFalse( $this->container->has( 'key' ) );
	}

	public function test_should_remove_shared_key() {
		$this->container->define( 'key', function () {
			return 'value';
		} );

		$this->container->remove( 'key' );

		$this->assertFalse( $this->container->has( 'key' ) );
	}

	public function test_should_implement_array_access() {
		$this->container['key'] = function () {
			return 'value';
		};

		$this->assertEquals( $this->container['key'], 'value' );
		$this->assertTrue( isset( $this->container['key'] ) );

		unset( $this->container['key'] );

		$this->assertFalse( isset( $this->container['key'] ) );
	}

	public function test_should_implement_iterator() {
		$this->container['key1'] = function () {
			return 'value';
		};
		$this->container->define( 'key2', 'value' );

		$count = 0;

		foreach ( $this->container as $key => $value ) {
			$this->assertContains( $key, array( 'key1', 'key2' ) );
			$this->assertEquals( 'value', $value );
			$count ++;
		}

		$this->assertSame( 2, $count );
	}
}
