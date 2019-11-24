<?php
namespace Intraxia\Jaxion\Core;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use ReflectionClass;

/**
 * HooksReader class.
 */
final class HooksReader {

	/**
	 * Reader instance.
	 *
	 * @var AnnotationReader
	 */
	private $reader;

	/**
	 * Construct a new HooksReader instance.
	 */
	private function __construct() {
		$this->reader = new AnnotationReader();
		AnnotationRegistry::registerFile(
			__DIR__ . '/Annotations.php'
		);
	}

	/**
	 * Get the Annotation reader.
	 *
	 * @return AnnotationReader
	 */
	private function reader() {
		return $this->reader;
	}

	/**
	 * Get the shared reader instance.
	 *
	 * @return HooksReader
	 */
	static private function instance() {
		static $instance;

		if ( ! $instance ) {
			$instance = new HooksReader();
		}

		return $instance;
	}

	/**
	 * Shared implementation of hook reading logic.
	 *
	 * @param  object   $target    Object to read annotations from.
	 * @param  \Closure $condition Whether the annotation should be registered.
	 * @return array
	 */
	static public function read( $target, $condition ) {
		$reader = static::instance()->reader();
		$rmethods = (new ReflectionClass( $target ))->getMethods();

		$hooks = [];

		foreach ( $rmethods as $rmethod ) {
			foreach ( $reader->getMethodAnnotations( $rmethod ) as $annotation ) {
				if ( $condition( $annotation ) ) {
					$hooks[] = [
						'hook' => $annotation->hook,
						'method' => $rmethod->getName(),
						'priority' => $annotation->priority,
						'args' => $annotation->args,
					];
				}
			}
		}

		return $hooks;
	}
}
