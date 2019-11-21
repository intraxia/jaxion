<?php
namespace Intraxia\Jaxion\Core;

/**
 * Class Config
 *
 * Configuration service to manage the
 * configuration data of the plugin or theme.
 *
 * @package    Intraxia\Jaxion
 * @subpackage Core
 */
class Config {
	/**
	 * Configuration type.
	 *
	 * @var ConfigType
	 */
	public $type;

	/**
	 * App entry file.
	 *
	 * @var string
	 */
	public $file;

	/**
	 * App url.
	 *
	 * @var string
	 */
	public $url;

	/**
	 * App path.
	 *
	 * @var string
	 */
	public $path;

	/**
	 * App slug.
	 *
	 * @var string
	 */
	public $slug;

	/**
	 * App basename.
	 *
	 * @var string
	 */
	public $basename;

	/**
	 * Loaded JSON configuration files.
	 *
	 * @var array
	 */
	private $json = array();

	/**
	 * Loaded PHP configuration files.
	 *
	 * @var array
	 */
	private $php = array();

	/**
	 * Config constructor.
	 *
	 * @param string $type
	 * @param string $file
	 */
	public function __construct( $type, $file ) {
		$this->type = new ConfigType( $type );
		$this->file = $file;

		switch ( $this->type->getValue() ) {
			case ConfigType::PLUGIN:
			case ConfigType::MU_PLUGIN:
				$this->url = plugin_dir_url( $file );
				$this->path = plugin_dir_path( $file );
				$this->slug = dirname( $this->basename = plugin_basename( $file ) );
				break;
			case ConfigType::THEME:
				$this->url = get_stylesheet_directory_uri() . '/';
				$this->path = get_stylesheet_directory() . '/';
				$this->slug = dirname( $this->basename = plugin_basename( $file ) );
				break;
		}
	}

	/**
	 * Load a JSON file from the resources folder.
	 *
	 * @param string $filename
	 *
	 * @return array|null
	 */
	public function get_json_resource( $filename ) {
		if ( isset( $this->json[ $filename ] ) ) {
			return $this->json[ $filename ];
		}

		$path = $this->path . 'resources/' . $filename . '.json';

		if ( ! file_exists( $path ) ) {
			return null;
		}

		$contents = file_get_contents( $path );

		if ( false === $contents ) {
			return null;
		}

		return $this->json[ $filename ] = json_decode( $contents, true );
	}

	/**
	 * Load a configuration PHP file from the config folder.
	 *
	 * @param string $filename
	 *
	 * @return array|null
	 */
	public function get_php_config( $filename ) {
		if ( isset( $this->php[ $filename ] ) ) {
			return $this->php[ $filename ];
		}

		$path = $this->path . 'resources/config/' . $filename . '.php';

		if ( ! file_exists( $path ) ) {
			return null;
		}

		return $this->php[ $filename ] = require $path;
	}
}
