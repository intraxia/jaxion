<?php
namespace Intraxia\Jaxion\Http;

use Intraxia\Jaxion\Contract\Http\Guard as GuardContract;
use Intraxia\Jaxion\Utility\Str;
use WP_Error;

/**
 * Class Guard
 *
 * Protects routes by validating that
 * the accessing user has required permissions.
 *
 * @package Intraxia\Jaxion
 * @subpackage Http
 */
class Guard implements GuardContract {
	/**
	 * Default options.
	 *
	 * @var array
	 */
	protected $defaults = array(
		'rule'     => 'public',
		'callback' => null,
	);

	/**
	 * Guard options.
	 *
	 * @var array
	 */
	protected $options;

	/**
	 * Instantiate a new Guard with provided options.
	 *
	 * @param array $options
	 */
	public function __construct( array $options = array() ) {
		$this->options = $this->set_defaults( $options );
	}

	/**
	 * Validates whether the current user is authorized.
	 *
	 * @return true|WP_Error
	 */
	public function authorized() {
		// if the rule is public, always authorized
		if ( 'public' === $this->options['rule'] ) {
			return true;
		}

		// enable passing in callback
		if ( 'callback' === $this->options['rule'] && is_callable( $this->options['callback'] ) ) {
			return call_user_func( $this->options['callback'] );
		}

		// map rule to method
		if ( method_exists( $this, $method = $this->options['rule'] ) ) {
			return $this->{$method}();
		}

		// disable in rule is misconfigused
		// @todo set up internal translations
		return new WP_Error( 'misconfigured_guard', __( 'Misconfigured guard rule', 'jaxion' ), [ 'status' => 500 ] );
	}

	/**
	 * Checks whether the current user can edit other's posts.
	 *
	 * @return bool|WP_Error
	 */
	protected function can_edit_others_posts() {
		return current_user_can( 'edit_others_posts' ) ?: $this->make_error();
	}

	/**
	 * Checks whether the user is currently logged in.
	 *
	 * @return bool|WP_Error
	 */
	protected function user_logged_in() {
		return is_user_logged_in() ?: $this->make_error();
	}

	/**
	 * Checks whether the user can manage the site options.
	 *
	 * @return bool|WP_Error
	 */
	protected function can_manage_options() {
		return current_user_can( 'manage_options' ) ?: $this->make_error();
	}

	/**
	 * Sets the default params for the Guard options.
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	protected function set_defaults( $options ) {
		// these are the valid options
		return wp_parse_args( $options, $this->defaults );
	}

	/**
	 * Create an unauthorized error object.
	 *
	 * @return WP_Error
	 */
	protected function make_error() {
		return new WP_Error( 'unauthorized', __( 'Unauthorized user', 'jaxion' ), array( 'status' => 401 ) );
	}
}
