<?php

/**
 * Mollie_Api_Routes
 *
 * Initiate custom wordpress api routes
 *
 * @version 1.0
 * @author Hristijan Nikoloski
 */

class Mollie_Api_Routes
{
	private static $instance = null;
	const METHOD = 'methods';
	const CALLBACK = 'callback';
	const PERMISSION_CALLBACK = 'permission_callback';

	/**
	 * Returns a single instance of this class.
	 */
	public static function getInstance()
	{

		if (null == self::$instance) {
			self::$instance = new Mollie_Api_Routes();
		}

		return self::$instance;
	}

	/**
	 * @var string Api namespace
	 */
	protected $namespace = MOLLIE_API_NAMESPACE;


	/**
	 * class constructor
	 */
	public function __construct()
	{
		// plugin initialization
		add_action('rest_api_init', [$this, 'register_routes']);
	}

	public function register_routes()
	{
		/**********  GET Routes  **********/

		// test
		register_rest_route($this->namespace, '/test', array(
			self::METHOD => WP_REST_Server::READABLE,
			self::CALLBACK => [new Mollie_Public_Actions(), 'testMethod'],
			self::PERMISSION_CALLBACK => function () {
				return true;
			}
		));

		// Fnugg Suggestions/AutoComplete
		register_rest_route($this->namespace, '/suggest', array(
			self::METHOD => WP_REST_Server::READABLE,
			self::CALLBACK => [new Mollie_Public_Actions(), 'fnuggSuggest'],
			self::PERMISSION_CALLBACK => function () {
				return true;
			}
		));

		// Fnugg Search
		register_rest_route($this->namespace, '/search', array(
			self::METHOD => WP_REST_Server::READABLE,
			self::CALLBACK => [new Mollie_Public_Actions(), 'fnuggSearch'],
			self::PERMISSION_CALLBACK => function () {
				return true;
			}
		));

		// Clear cache
		register_rest_route($this->namespace, '/clear-cache', array(
			self::METHOD => WP_REST_Server::READABLE,
			self::CALLBACK => [new Mollie_Public_Actions(), 'clearCache'],
			self::PERMISSION_CALLBACK => function () {
				return true;
			}
		));

		/**********  POST Routes  **********/
	}
}
