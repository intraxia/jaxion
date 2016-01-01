<?php
namespace Intraxia\Jaxion\Test\Assets;

use Intraxia\Jaxion\Assets\RegisterServiceProvider;
use Mockery;

class RegisterServiceProviderTest extends \PHPUnit_Framework_TestCase {
	public function test_should_define_register_on_container() {
		$provider  = new RegisterServiceProvider;
		$container = Mockery::mock( 'Intraxia\Jaxion\Contract\Core\Container' );
		$container->shouldReceive( 'fetch' )
			->twice()
			->andReturn( 'test.com/', '1.0.0' );
		$container->shouldReceive( 'define' )
			->once()
			->with(
				array( 'register' => 'Intraxia\Jaxion\Contract\Assets\Register' ),
				Mockery::type( 'Intraxia\Jaxion\Contract\Assets\Register' )
			);

		$provider->register( $container );
	}

	protected function tearDown() {
		parent::tearDown();
		Mockery::close();
	}
}
