<?php
namespace Intraxia\Jaxion\Http;

use Intraxia\Jaxion\Contract\Core\Container;
use Intraxia\Jaxion\Contract\Core\ServiceProvider;

/**
 * Class RouterServiceProvider
 *
 * @package Intraxia\Jaxion
 * @subpackage Http
 */
class RouterServiceProvider implements ServiceProvider {
	/**
	 * {@inheritDoc}
	 *
	 * @param Container $container
	 */
	public function register( Container $container ) {
		$container->define( array( 'router' => 'Intraxia\Jaxion\Http\Router' ), $router = new Router );

		$this->add_routes( $router );
	}

	/**
	 * Registers the routes on the generated Router.
	 *
	 * This is a no-op by default by can be overwritten by the implementing developer
	 * to provide a single, clean location to register their routes.
	 *
	 * @param Router $router
	 *
	 * @codeCoverageIgnore
	 */
	protected function add_routes( Router $router ) {
		// no-op
	}
}
