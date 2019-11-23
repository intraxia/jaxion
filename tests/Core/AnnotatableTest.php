<?php
namespace Intraxia\Jaxion\Test\Core;

use Doctrine\Common\Annotations\AnnotationException;
use Intraxia\Jaxion\Core\Annotatable;
use Intraxia\Jaxion\Core\Annotation\Action;
use Intraxia\Jaxion\Core\Annotation\Filter;
use Mockery;
use WP_Mock;

class AnnotatableTest extends \PHPUnit_Framework_TestCase {
	public function test_should_throw_without_action_hook() {
		$no_hook = new ActionWithNoHook();

		$this->expectException( AnnotationException::class );

		$no_hook->action_hooks();
	}

	public function test_should_get_actions_hooks_with_method() {
		$no_hook = new ActionWithHook();

		$hooks = $no_hook->action_hooks();

		$this->assertSame( $hooks, [
			[
				'hook' => 'target_hook',
				'method' => 'action_method',
				'priority' => 10,
				'args' => 1,
			],
		] );
	}

	public function test_should_get_actions_hooks_with_method_and_priority() {
		$no_hook = new ActionWithHookAndPriority();

		$hooks = $no_hook->action_hooks();

		$this->assertSame( $hooks, [
			[
				'hook' => 'target_hook',
				'method' => 'action_method',
				'priority' => 20,
				'args' => 1,
			],
		] );
	}

	public function test_should_get_actions_hooks_with_method_and_args() {
		$no_hook = new ActionWithHookAndArgs();

		$hooks = $no_hook->action_hooks();

		$this->assertSame( $hooks, [
			[
				'hook' => 'target_hook',
				'method' => 'action_method',
				'priority' => 10,
				'args' => 3,
			],
		] );
	}

	public function test_should_get_actions_hooks_with_all() {
		$no_hook = new ActionWithAll();

		$hooks = $no_hook->action_hooks();

		$this->assertSame( $hooks, [
			[
				'hook' => 'target_hook',
				'method' => 'action_method',
				'priority' => 20,
				'args' => 3,
			],
		] );
	}

	public function test_should_throw_without_filter_hook() {
	  $no_hook = new FilterWithNoHook();

	  $this->expectException( AnnotationException::class );

	  $no_hook->filter_hooks();
	}

	public function test_should_get_filters_hooks_with_method() {
	  $no_hook = new FilterWithHook();

	  $hooks = $no_hook->filter_hooks();

	  $this->assertSame( $hooks, [
	    [
	      'hook' => 'target_hook',
	      'method' => 'filter_method',
	      'priority' => 10,
	      'args' => 1,
	    ],
	  ] );
	}

	public function test_should_get_filters_hooks_with_method_and_priority() {
	  $no_hook = new FilterWithHookAndPriority();

	  $hooks = $no_hook->filter_hooks();

	  $this->assertSame( $hooks, [
	    [
	      'hook' => 'target_hook',
	      'method' => 'filter_method',
	      'priority' => 20,
	      'args' => 1,
	    ],
	  ] );
	}

	public function test_should_get_filters_hooks_with_method_and_args() {
	  $no_hook = new FilterWithHookAndArgs();

	  $hooks = $no_hook->filter_hooks();

	  $this->assertSame( $hooks, [
	    [
	      'hook' => 'target_hook',
	      'method' => 'filter_method',
	      'priority' => 10,
	      'args' => 3,
	    ],
	  ] );
	}

	public function test_should_get_filters_hooks_with_all() {
	  $no_hook = new FilterWithAll();

	  $hooks = $no_hook->filter_hooks();

	  $this->assertSame( $hooks, [
	    [
	      'hook' => 'target_hook',
	      'method' => 'filter_method',
	      'priority' => 20,
	      'args' => 3,
	    ],
	  ] );
	}
}

class ActionWithNoHook {
	use Annotatable;

	/**
	 * @Action()
	 */
	public function action_method() {
		return 'hello';
	}
}

class ActionWithHook {
	use Annotatable;

	/**
	 * @Action(
	 *   hook="target_hook"
	 * )
	 */
	public function action_method() {
		return 'hello';
	}
}

class ActionWithHookAndPriority {
	use Annotatable;

	/**
	 * @Action(
	 *   hook="target_hook",
	 *   priority=20
	 * )
	 */
	public function action_method() {
		return 'hello';
	}
}

class ActionWithHookAndArgs {
	use Annotatable;

	/**
	 * @Action(
	 *   hook="target_hook",
	 *   args=3
	 * )
	 */
	public function action_method() {
		return 'hello';
	}
}

class ActionWithAll {
	use Annotatable;

	/**
	 * @Action(
	 *   hook="target_hook",
	 *   args=3,
	 *   priority=20
	 * )
	 */
	public function action_method() {
		return 'hello';
	}
}

class FilterWithNoHook {
	use Annotatable;

	/**
	 * @Filter()
	 */
	public function filter_method() {
		return 'hello';
	}
}

class FilterWithHook {
	use Annotatable;

	/**
	 * @Filter(
	 *   hook="target_hook"
	 * )
	 */
	public function filter_method() {
		return 'hello';
	}
}

class FilterWithHookAndPriority {
	use Annotatable;

	/**
	 * @Filter(
	 *   hook="target_hook",
	 *   priority=20
	 * )
	 */
	public function filter_method() {
		return 'hello';
	}
}

class FilterWithHookAndArgs {
	use Annotatable;

	/**
	 * @Filter(
	 *   hook="target_hook",
	 *   args=3
	 * )
	 */
	public function filter_method() {
		return 'hello';
	}
}

class FilterWithAll {
	use Annotatable;

	/**
	 * @Filter(
	 *   hook="target_hook",
	 *   args=3,
	 *   priority=20
	 * )
	 */
	public function filter_method() {
		return 'hello';
	}
}
