<?php
namespace Intraxia\Jaxion\Test\Model;

use Intraxia\Jaxion\Test\Stubs\MetaBase;
use Intraxia\Jaxion\Test\Stubs\TableBase;
use Mockery;
use WP_Post;

/**
 * @group model
 */
class BaseTest extends \PHPUnit_Framework_TestCase {
	public function setUp() {
		parent::setUp();
		Mockery::mock( 'overload:WP_Post' );
		Mockery::mock( 'overload:WP_REST_Response' );
	}

	public function test_should_construct_to_meta_with_no_table() {
		$base = new MetaBase( array(
			'test' => 'value'
		) );

		$attributes = $base->get_attributes();
		$this->assertSame( 'value', $attributes['test'] );
		$this->assertSame( 'value', $base->test );
	}

	public function test_should_assign_to_meta_with_no_table() {
		$base       = new MetaBase();
		$base->test = 'value';

		$attributes = $base->get_attributes();
		$this->assertSame( 'value', $attributes['test'] );
		$this->assertSame( 'value', $base->test );
	}

	public function test_should_construct_to_table() {
		$base = new TableBase( array(
			'test' => 'value'
		) );

		$attributes = $base->get_attributes();
		$this->assertSame( 'value', $attributes['test'] );
		$this->assertSame( 'value', $base->test );
	}

	public function test_should_assign_to_table() {
		$base       = new TableBase();
		$base->test = 'value';

		$attributes = $base->get_attributes();
		$this->assertSame( 'value', $attributes['test'] );
		$this->assertSame( 'value', $base->test );
	}

	public function test_should_not_assign_post() {
		$base = new TableBase();

		$this->assertFalse( $base->get_underlying_post() );
	}

	public function test_should_copy_attributes_to_original() {
		$model = new MetaBase( array(
			'test' => 'value',
			'post' => new WP_Post,
		) );

		$model->sync_original();

		$original   = $model->get_original_attributes();
		$attributes = $model->get_attributes();

		$this->assertSame( $original['test'], $attributes['test'] );
		$this->assertNotSame( $model->get_original_post(), $model->get_underlying_post() );
	}

	public function tearDown() {
		parent::tearDown();
		Mockery::close();
	}
}
