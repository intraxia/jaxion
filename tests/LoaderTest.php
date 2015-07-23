<?php
namespace Intraxia\Jaxion\Test;

use Intraxia\Jaxion\Loader;
use Mockery;
use WP_Mock;

class LoaderTest extends \PHPUnit_Framework_TestCase
{
    protected $app;

    /**
     * @var Loader
     */
    protected $loader;

    public function setUp()
    {
        WP_Mock::setUp();
        $this->app = Mockery::mock('Intraxia\Jaxion\Application')->shouldDeferMissing();
        $this->loader = new Loader();
    }

    public function testShouldRunOnPluginsLoadedHook()
    {
        WP_Mock::expectActionAdded('plugins_loaded', array($this->loader, 'run'));
        $this->loader->register($this->app);
    }

    public function testShouldAddAction()
    {
        $service = new \stdClass;
        $service->actions = array(
            array(
                'hook' => 'test_action',
                'method' => 'test',
                'priority' => 15,
                'args' => 2,
            )
        );

        $this->app
            ->shouldReceive('valid')
            ->twice()
            ->andReturn(true, false)
            ->shouldReceive('current')
            ->andReturn($service)
            ->shouldReceive('key')
            ->andReturn('test_service');

        WP_Mock::expectActionAdded('plugins_loaded', array($this->loader, 'run'));
        WP_Mock::expectActionAdded('test_action', array($service, 'test'), 15, 2);

        $this->loader->register($this->app);
        $this->loader->run();
    }

    public function testShouldAddFilter()
    {
        $service = new \stdClass;
        $service->filters = array(
            array(
                'hook' => 'test_filter',
                'method' => 'test',
                'priority' => 15,
                'args' => 2,
            )
        );

        $this->app
            ->shouldReceive('valid')
            ->twice()
            ->andReturn(true, false)
            ->shouldReceive('current')
            ->andReturn($service)
            ->shouldReceive('key')
            ->andReturn('test_service');

        WP_Mock::expectActionAdded('plugins_loaded', array($this->loader, 'run'));
        WP_Mock::expectFilterAdded('test_filter', array($service, 'test'), 15, 2);

        $this->loader->register($this->app);
        $this->loader->run();
    }

    public function tearDown()
    {
        WP_Mock::tearDown();
    }
}
