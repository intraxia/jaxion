<?php
namespace Intraxia\Jaxion\Test\Http;

use Intraxia\Jaxion\Http\Filter;
use PHPUnit_Framework_TestCase;
use WP_Mock;

class FilterTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
        WP_Mock::setUp();
    }

    public function testShouldSetArgAsRequired()
    {
        $filter = new Filter(array('param' => 'required'));

        $this->assertEquals(array('param' => array('required' => true)), $filter->rules());
    }

    public function testShouldSetArgDefault()
    {
        $filter = new Filter(array('param' => 'default:value'));

        $this->assertEquals(array('param' => array('default' => 'value')), $filter->rules());
    }

    public function testShouldSetEmptyStringDefaultIfNoValue()
    {
        $filter = new Filter(array('param' => 'default'));

        $this->assertEquals(array('param' => array('default' => '')), $filter->rules());

        $filter = new Filter(array('param' => 'default:'));

        $this->assertEquals(array('param' => array('default' => '')), $filter->rules());
    }

    public function testShouldValidateNumber()
    {
        $filter = new Filter(array('param' => 'integer'));

        $this->assertEquals(array('param' => array(
            'validate_callback' => array($filter, 'validateInteger'),
            'sanitize_callback' => array($filter, 'makeInteger'),
        )), $filter->rules());
    }

    public function testIntegerStringShouldValidate()
    {
        $filter = new Filter();

        $this->assertTrue($filter->validateInteger('1234567890'));
    }

    public function testIntegerIntShouldValidate()
    {
        $filter = new Filter();

        $this->assertTrue($filter->validateInteger(1234567890));
    }

    public function testIntegerStringShouldBeCast()
    {
        $filter = new Filter();

        $this->assertEquals(1234567890, $filter->makeInteger('1234567890'));
    }

    public function testIntegerShouldBeReturned()
    {
        $filter = new Filter();

        $this->assertEquals(1234567890, $filter->makeInteger(1234567890));
    }

    public function testShouldTakeDefaultAndRule()
    {
        $filter = new Filter(array(
            'param' => 'default:value|integer'
        ));

        $this->assertEquals(array('param' => array(
            'default' => 'value',
            'validate_callback' => array($filter, 'validateInteger'),
            'sanitize_callback' => array($filter, 'makeInteger'),
        )), $filter->rules());
    }
}
