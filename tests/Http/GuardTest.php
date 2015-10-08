<?php
namespace Intraxia\Jaxion\Test\Http;

use Intraxia\Jaxion\Http\Guard;
use Mockery;
use WP_Mock;

class GuardTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
        WP_Mock::setUp();

        WP_Mock::wpFunction('wp_parse_args', array(
            'times' => 1,
            'return' => function ($options, $defaults) {
                return array_merge($defaults, $options);
            }
        ));
    }

    public function testShouldPassByDefault()
    {
        $guard = new Guard();

        $this->assertTrue($guard->authorized());
    }

    public function testShouldValidateWithCallback()
    {
        $called = false;
        $guard = new Guard(array(
            'rule' => 'callback',
            'callback' => function() use (&$called) {
                return $called = true;
            }
        ));

        $this->assertTrue($guard->authorized());
        $this->assertTrue($called);
    }

    public function testShouldReturnTrueIfCanEditOthersPosts()
    {
        WP_Mock::wpFunction('current_user_can', array(
            'times' => 1,
            'args' => array('edit_others_posts'),
            'return' => true,
        ));

        $guard = new Guard(array(
            'rule' => 'can_edit_others_posts',
        ));

        $this->assertTrue($guard->authorized());
    }

    public function testShouldReturnWPErrorIfCantEditOthersPosts()
    {
        WP_Mock::wpFunction('current_user_can', array(
            'times' => 1,
            'args' => array('edit_others_posts'),
            'return' => false,
        ));
        Mockery::mock('overload:WP_Error');
        WP_Mock::wpFunction('__', array(
            'times' => 1,
        ));

        $guard = new Guard(array(
            'rule' => 'can_edit_others_posts',
        ));

        $this->assertInstanceOf('WP_Error', $guard->authorized());
    }

    public function testShouldReturnErrorIfNoMatchedRule()
    {
        WP_Mock::wpFunction('__', array(
            'times' => 1,
        ));

        Mockery::mock('overload:WP_Error');

        $guard = new Guard(array(
            'rule' => 'this_is_not_a_real_rule',
        ));

        $this->assertInstanceOf('WP_Error', $guard->authorized());
    }

    public function tearDown()
    {
        parent::tearDown();
        WP_Mock::tearDown();
        Mockery::close();
    }
}
