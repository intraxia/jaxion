<?php
namespace Intraxia\Jaxion\Http;

/**
 * Class Router
 *
 * A simplified interface for registering routes with the WP-API.
 *
 * @package Intraxia\Jaxion
 * @subpackage Http
 */
class Router
{
    /**
     * Action hooks for the Router.
     *
     * @var array
     */
    public $actions = array(
        array(
            'method' => 'register',
            'hook' => 'rest_api_init',
        ),
    );

    /**
     * Resource's vendor prefix.
     *
     * @var string
     */
    protected $vendor;

    /**
     * Resource's version.
     *
     * @var int
     */
    protected $version;

    /**
     * Valid methods and their HTTP verb(s).
     *
     * @var array
     */
    private $methods = array(
        'get' => 'GET',
        'post' => 'POST',
        'put' => 'PUT',
        'patch' => 'PATCH',
        'delete' => 'DELETE',
        'editable' => 'POST, PUT, PATCH',
        'all' => 'GET, POST, PUT, PATCH, DELETE',
    );

    /**
     * Endpoints registered for the resource.
     *
     * @var Endpoint[]
     */
    protected $endpoints = array();

    /**
     * Returns all the resource endpoints.
     *
     * @return Endpoint[]
     */
    public function getEndpoints()
    {
        return $this->endpoints;
    }

    /**
     * Sets the resource's vendor prefix.
     *
     * @param string $vendor
     */
    public function setVendor($vendor)
    {
        $this->vendor = $vendor;
    }

    /**
     * Sets the resource's version.
     *
     * @param int $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * Registers all of the routes with the WP-API.
     *
     * Runs on the `rest_api_init` hook. Registers all of the routes loaded
     * on the router into the WordPress REST API.
     *
     * @throws VendorNotSetException
     * @throws VersionNotSetException
     */
    public function register()
    {
        if (!$this->vendor) {
            throw new VendorNotSetException;
        }

        if (!$this->version) {
            throw new VersionNotSetException;
        }

        foreach ($this->endpoints as $endpoint) {
            register_rest_route(
                $this->getNamespace(),
                $endpoint->getRoute(),
                $endpoint->getOptions()
            );
        }
    }

    /**
     * Registers a set of routes with a shared set of options.
     *
     * Allows you to group routes together with shared set of options, including
     * a route prefix, shared guards, and common parameter validation or sanitization.
     *
     * @param array    $options
     * @param callable $callback
     */
    public function group(array $options, $callback)
    {
        $router = new static;

        call_user_func($callback, $router);

        foreach ($router->getEndpoints() as $endpoint) {
            $this->endpoints[] = $this->setOptions($endpoint, $options);
        }
    }

    /**
     * Magic __call method.
     *
     * All of the endpoints registration method calls pass through here. This validates whether the method
     * is a valid endpoint type to register, and creates a new endpoint with the passed options.
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return Endpoint
     *
     * @throws UnknownMethodException
     * @throws MissingArgumentException
     */
    public function __call($name, $arguments)
    {
        if (!in_array($name, array_keys($this->methods))) {
            throw new UnknownMethodException;
        }

        // array_merge ensures we have 3 elements
        list($route, $callback, $options) = array_merge($arguments, array(null, null, null));

        if (!$route || !$callback) {
            throw new MissingArgumentException;
        }

        $endpoint = new Endpoint($route, $this->methods[$name], $callback);

        if ($options && is_array($options)) {
            $endpoint = $this->setOptions($endpoint, $options);
        }

        return $this->endpoints[] = $endpoint;
    }

    /**
     * Sets the passed options on the endpoint.
     *
     * Only sets endpoints matching setters in the Endpoint class.
     *
     * @param Endpoint $endpoint
     * @param array    $options
     *
     * @return Endpoint
     * @throws MalformedRouteException
     */
    protected function setOptions(Endpoint $endpoint, array $options)
    {
        if (isset($options['guard'])) {
            $endpoint->setGuard($options['guard']);
        }

        if (isset($options['filter'])) {
            $endpoint->setFilter($options['filter']);
        }

        if (isset($options['prefix'])) {
            $endpoint->setPrefix($options['prefix']);
        }

        return $endpoint;
    }

    /**
     * Generates the resource's namespace.
     *
     * @return string
     */
    protected function getNamespace()
    {
        return $this->vendor . '/v' . $this->version;
    }
}
