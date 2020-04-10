<?php
namespace Intraxia\Jaxion\Contract\Assets;

use Intraxia\Jaxion\Contract\Core\HasActions;

interface Register extends HasActions {

	/**
	 * Provides a method to register new scripts outside of the constructor.
	 *
	 * @param array $script
	 */
	public function register_script( $script );

	/**
	 * Provides a method to register new styles outside of the constructor.
	 *
	 * @param array $style
	 */
	public function register_style( $style );

	/**
	 * Enqueues the web & shared scripts on the Register.
	 */
	public function enqueue_web_scripts();

	/**
	 * Enqueues the web & shared styles on the Register.
	 */
	public function enqueue_web_styles();

	/**
	 * Enqueues the admin & shared scripts on the Register.
	 *
	 * @param string $hook Passes a string representing the current page.
	 */
	public function enqueue_admin_scripts( $hook );

	/**
	 * Enqueues the admin & shared styles on the Register.
	 *
	 * @param string $hook Passes a string representing the current page.
	 */
	public function enqueue_admin_styles( $hook );

	/**
	 * Enqueues the block styles & registers the block.
	 */
	public function register_blocks();
}
