<?php

/**
 * Mollie Starter
 *
 * Main plugin class for registering/unregistering plugin functionality
 *
 * @version 1.0
 * @author Hristijan Nikoloski
 */
class Mollie_Starter
{


	private static $instance = null;


	/**
	 * Returns an singleton instance of this class.
	 */
	public static function getInstance()
	{

		if (null == self::$instance) {
			self::$instance = new Mollie_Starter();
		}

		return self::$instance;
	}

	/**
	 * class constructor
	 */
	public function __construct()
	{
		// autoload classes
		spl_autoload_register([$this, 'autoload']);
		// add_action('after_setup_theme', [$this, 'init']);
		add_action('plugins_loaded', [$this, 'init']);
	}

	public static function autoload($class)
	{

		$class = 'class-' . str_replace('_', '-', strtolower($class));

		//locations of all class files
		$dirs = array(
			MOLLIE_PLUGIN_DIR . "includes/classes",
			MOLLIE_PLUGIN_DIR . "includes/api",
			MOLLIE_PLUGIN_DIR . "includes/api/controllers",
		);
		//autoload requested class
		foreach ($dirs as $dir) {
			if (is_file($file = "$dir/$class.php")) {
				require_once($file);
				return;
			}
		}
	}



	/**
	 * Initializes WordPress hooks
	 */
	public function init()
	{
		Mollie_Api_Routes::getInstance();
	}
}
