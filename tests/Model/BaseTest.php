<?php
namespace Intraxia\Jaxion\Test\Model;

use Intraxia\Jaxion\Test\Stubs\MetaModel;
use Intraxia\Jaxion\Test\Stubs\TableModel;
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
		$base = new MetaModel( array(
			'test' => 'value'
		) );

		$attributes = $base->get_attributes();
		$this->assertSame( 'value', $attributes['test'] );
		$this->assertSame( 'value', $base->test );
	}

	public function test_should_assign_to_meta_with_no_table() {
		$base       = new MetaModel();
		$base->test = 'value';

		$attributes = $base->get_attributes();
		$this->assertSame( 'value', $attributes['test'] );
		$this->assertSame( 'value', $base->test );
	}

	public function test_should_construct_to_table() {
		$base = new TableModel( array(
			'test' => 'value'
		) );

		$attributes = $base->get_attributes();
		$this->assertSame( 'value', $attributes['test'] );
		$this->assertSame( 'value', $base->test );
	}

	public function test_should_assign_to_table() {
		$base       = new TableModel();
		$base->test = 'value';

		$attributes = $base->get_attributes();
		$this->assertSame( 'value', $attributes['test'] );
		$this->assertSame( 'value', $base->test );
	}

	public function test_should_not_assign_post() {
		$base = new TableModel();

		$this->assertFalse( $base->get_underlying_post() );
	}

	public function test_should_copy_attributes_to_original() {
		$model = new MetaModel( array(
			'test' => 'value',
			'post' => new WP_Post,
		) );

		$model->sync_original();

		$original   = $model->get_original_attributes();
		$attributes = $model->get_attributes();

		$this->assertSame( $original['test'], $attributes['test'] );
		$this->assertNotSame( $model->get_original_post(), $model->get_underlying_post() );
	}

	public function test_should_clear_current_model_attributes() {
		$model = new MetaModel( array(
			'test' => 'value',
			'post' => $post = new WP_Post,
		) );

		$model->clear();

		$this->setExpectedException( 'Intraxia\Jaxion\Axolotl\PropertyDoesNotExistException' );

		$model->get_attribute( 'test' );

		$this->assertSame( $post, $model->get_underlying_post() );
	}

	public function tearDown() {
		parent::tearDown();
		Mockery::close();
	}
}
