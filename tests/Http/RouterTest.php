<?php
namespace Intraxia\Jaxion\Test\Http;

use Intraxia\Jaxion\Http\Router;
use Mockery;
use WP_Mock;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Router
     */
    protected $router;

    public function setUp()
    {
        parent::setUp();
        WP_Mock::setUp();

        $this->router = new Router();
        $this->router->setVendor('jaxion');
        $this->router->setVersion(1);
    }

    public function testShouldRequireVendor()
    {
        $this->setExpectedException('Intraxia\Jaxion\Http\VendorNotSetException');

        $router = new Router();
        $router->setVersion(1);
        $router->register();
    }

    public function testShouldRequireVersion()
    {
        $this->setExpectedException('Intraxia\Jaxion\Http\VersionNotSetException');

        $router = new Router();
        $router->setVendor('jaxion');
        $router->register();
    }

    public function testShouldThrowUnknownMethod()
    {
        $this->setExpectedException('Intraxia\Jaxion\Http\UnknownMethodException');

        $this->router->postable();
    }

    public function testShouldRequireRoute()
    {
        $this->setExpectedException('Intraxia\Jaxion\Http\MissingArgumentException');

        $this->router->get();
    }

    public function testShouldRequireCallback()
    {
        $this->setExpectedException('Intraxia\Jaxion\Http\MissingArgumentException');

        $this->router->get('/widgets');
    }

    public function testShouldRejectMalformedRoute()
    {
        $callback = function () {
            return true;
        };

        $this->setExpectedException('Intraxia\Jaxion\Http\MalformedRouteException');

        $this->router->get('/widgets/', $callback);

        $this->setExpectedException('Intraxia\Jaxion\Http\MalformedRouteException');

        $this->router->get('widgets', $callback);
    }

    public function testShouldRegisterNewGetRoute()
    {
        $callback = function () {
            return true;
        };

        $this->router->get('/widgets', $callback);

        WP_Mock::wpFunction('register_rest_route', array(
            'times' => 1,
            'args' => array(
                'jaxion/v1',
                '/widgets',
                array(
                    'methods'  => 'GET',
                    'callback' => $callback,
                ),
            )
        ));

        $this->router->register();
    }

    public function testShouldRegisterNewPostRoute()
    {
        $callback = function () {
            return true;
        };

        $this->router->post('/widgets', $callback);

        WP_Mock::wpFunction('register_rest_route', array(
            'times' => 1,
            'args' => array(
                'jaxion/v1',
                '/widgets',
                array(
                    'methods'  => 'POST',
                    'callback' => $callback,
                ),
            )
        ));

        $this->router->register();
    }

    public function testShouldRegisterNewPutRoute()
    {
        $callback = function () {
            return true;
        };

        $this->router->put('/widgets', $callback);

        WP_Mock::wpFunction('register_rest_route', array(
            'times' => 1,
            'args' => array(
                'jaxion/v1',
                '/widgets',
                array(
                    'methods'  => 'PUT',
                    'callback' => $callback,
                ),
            )
        ));

        $this->router->register();
    }

    public function testShouldRegisterNewPatchRoute()
    {
        $callback = function () {
            return true;
        };

        $this->router->patch('/widgets', $callback);

        WP_Mock::wpFunction('register_rest_route', array(
            'times' => 1,
            'args' => array(
                'jaxion/v1',
                '/widgets',
                array(
                    'methods'  => 'PATCH',
                    'callback' => $callback,
                ),
            )
        ));

        $this->router->register();
    }

    public function testShouldRegisterNewDeleteRoute()
    {
        $callback = function () {
            return true;
        };

        $this->router->delete('/widgets', $callback);

        WP_Mock::wpFunction('register_rest_route', array(
            'times' => 1,
            'args' => array(
                'jaxion/v1',
                '/widgets',
                array(
                    'methods'  => 'DELETE',
                    'callback' => $callback,
                ),
            )
        ));

        $this->router->register();
    }

    public function testShouldRegisterNewEditableRoute()
    {
        $callback = function () {
            return true;
        };

        $this->router->editable('/widgets', $callback);

        WP_Mock::wpFunction('register_rest_route', array(
            'times' => 1,
            'args' => array(
                'jaxion/v1',
                '/widgets',
                array(
                    'methods'  => 'POST, PUT, PATCH',
                    'callback' => $callback,
                ),
            )
        ));

        $this->router->register();
    }

    public function testShouldRegisterNewAllMethodRoute()
    {
        $callback = function () {
            return true;
        };

        $this->router->all('/widgets', $callback);

        WP_Mock::wpFunction('register_rest_route', array(
            'times' => 1,
            'args' => array(
                'jaxion/v1',
                '/widgets',
                array(
                    'methods'  => 'GET, POST, PUT, PATCH, DELETE',
                    'callback' => $callback,
                ),
            )
        ));

        $this->router->register();
    }

    public function testShouldApplyGuard()
    {
        $callback = function () {
            return true;
        };
        $guard = Mockery::mock('Intraxia\Jaxion\Contract\Http\Guard');

        $this->router->get('/widgets', $callback, array(
            'guard' => $guard,
        ));

        WP_Mock::wpFunction('register_rest_route', array(
            'times' => 1,
            'args' => array(
                'jaxion/v1',
                '/widgets',
                array(
                    'methods'  => 'GET',
                    'callback' => $callback,
                    'permission_callback' => array($guard, 'authorized'),
                ),
            )
        ));

        $this->router->register();
    }

    public function testShouldApplyFilter()
    {
        $callback = function () {
            return true;
        };
        $args = array('ID' => array(
            'validate_callback' => 'is_int'
        ));
        $filter = Mockery::mock('Intraxia\Jaxion\Contract\Http\Filter');
        $filter
            ->shouldReceive('rules')
            ->once()
            ->andReturn($args);

        $this->router->get('/widgets', $callback, array(
            'filter' => $filter,
        ));

        WP_Mock::wpFunction('register_rest_route', array(
            'times' => 1,
            'args' => array(
                'jaxion/v1',
                '/widgets',
                array(
                    'methods'  => 'GET',
                    'callback' => $callback,
                    'args' => $args,
                ),
            )
        ));

        $this->router->register();
    }

    public function testShouldIgnoreOtherOptions()
    {
        $callback = function () {
            return true;
        };

        $this->router->get('/widgets', $callback, array(
            'random' => 'random',
        ));

        WP_Mock::wpFunction('register_rest_route', array(
            'times' => 1,
            'args' => array(
                'jaxion/v1',
                '/widgets',
                array(
                    'methods'  => 'GET',
                    'callback' => $callback,
                ),
            )
        ));

        $this->router->register();
    }

    public function testShouldRejectMalformedPrefix()
    {
        $callback = function () {
            return true;
        };

        $this->setExpectedException('Intraxia\Jaxion\Http\MalformedRouteException');

        $this->router->group(array('prefix' => 'widgets'), function ($router) use ($callback) {
            $router->get('/first', $callback);
            $router->post('/second', $callback);
        });

        $this->setExpectedException('Intraxia\Jaxion\Http\MalformedRouteException');

        $this->router->group(array('prefix' => '/widgets/'), function ($router) use ($callback) {
            $router->get('/first', $callback);
            $router->post('/second', $callback);
        });
    }

    public function testShouldApplyPrefixToGroup()
    {
        $callback = function () {
            return true;
        };

        $this->router->group(array('prefix' => '/widgets'), function ($router) use ($callback) {
            $router->get('/first', $callback);
            $router->post('/second', $callback);
        });

        WP_Mock::wpFunction('register_rest_route', array(
            'times' => 1,
            'args' => array(
                'jaxion/v1',
                '/widgets/first',
                array(
                    'methods'  => 'GET',
                    'callback' => $callback,
                ),
            )
        ));

        WP_Mock::wpFunction('register_rest_route', array(
            'times' => 1,
            'args' => array(
                'jaxion/v1',
                '/widgets/second',
                array(
                    'methods'  => 'POST',
                    'callback' => $callback,
                ),
            )
        ));

        $this->router->register();
    }

    public function testShouldApplyGuardToGroup()
    {
        $callback = function () {
            return true;
        };
        $guard = Mockery::mock('Intraxia\Jaxion\Contract\Http\Guard');

        $this->router->group(array('guard' => $guard), function ($router) use ($callback) {
            $router->get('/first', $callback);
            $router->post('/second', $callback);
        });

        WP_Mock::wpFunction('register_rest_route', array(
            'times' => 1,
            'args' => array(
                'jaxion/v1',
                '/first',
                array(
                    'methods'  => 'GET',
                    'callback' => $callback,
                    'permission_callback' => array($guard, 'authorized'),
                ),
            )
        ));

        WP_Mock::wpFunction('register_rest_route', array(
            'times' => 1,
            'args' => array(
                'jaxion/v1',
                '/second',
                array(
                    'methods'  => 'POST',
                    'callback' => $callback,
                    'permission_callback' => array($guard, 'authorized'),
                ),
            )
        ));

        $this->router->register();
    }

    public function testShouldApplyFilterToGroup()
    {
        $callback = function () {
            return true;
        };
        $args = array('ID' => array(
            'validate_callback' => 'is_int'
        ));
        $filter = Mockery::mock('Intraxia\Jaxion\Contract\Http\Filter');
        $filter
            ->shouldReceive('rules')
            ->twice()
            ->andReturn($args);

        $this->router->group(array('filter' => $filter), function ($router) use ($callback) {
            $router->get('/first', $callback);
            $router->post('/second', $callback);
        });

        WP_Mock::wpFunction('register_rest_route', array(
            'times' => 1,
            'args' => array(
                'jaxion/v1',
                '/first',
                array(
                    'methods'  => 'GET',
                    'callback' => $callback,
                    'args' => $args,
                ),
            )
        ));

        WP_Mock::wpFunction('register_rest_route', array(
            'times' => 1,
            'args' => array(
                'jaxion/v1',
                '/second',
                array(
                    'methods'  => 'POST',
                    'callback' => $callback,
                    'args' => $args,
                ),
            )
        ));

        $this->router->register();
    }

    public function tearDown()
    {
        parent::tearDown();
        WP_Mock::tearDown();
    }
}
