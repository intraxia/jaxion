<?php
namespace Intraxia\Jaxion\Assets;

use Intraxia\Jaxion\Contract\Core\Container;
use Intraxia\Jaxion\Contract\Core\ServiceProvider;

/**
 * Class RegisterServiceProvider
 * @package Intraxia\Jaxion
 * @subpackage Assets
 */
class RegisterServiceProvider implements ServiceProvider {
	/**
	 * {@inheritDoc}
	 *
	 * @param Container $container
	 */
	public function register( Container $container ) {
		$container->define(
			array( 'register' => 'Intraxia\Jaxion\Contract\Assets\Register' ),
			$register = new Register( $container->fetch( 'url' ), $container->fetch( 'version' ) )
		);

		$this->add_assets( $register );
	}

	/**
	 * Registers the assets on the generated Register.
	 *
	 * This is a no-op by default by can be overwritten by the implementing developer
	 * to provide a single, clean location to register their assets.
	 *
	 * @param Register $register
	 *
	 * @codeCoverageIgnore
	 */
	protected function add_assets( Register $register ) {
		// no-op
	}
}
