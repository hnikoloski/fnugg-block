<?php

/**
 *  Mollie_Public_Actions
 *
 * Controller for api routes
 *
 * @version 1.0
 * @author Hristijan Nikoloski
 */

class Mollie_Public_Actions
{
	private static $instance = null;

	/**
	 * Returns a single instance of this class.
	 */
	public static function getInstance()
	{

		if (null == self::$instance) {
			self::$instance = new Mollie_Public_Actions();
		}

		return self::$instance;
	}

	/**
	 * Test method
	 */
	public static function testMethod(WP_REST_Request $request)
	{
		return new WP_REST_Response(['status' => 'OK', 'message' => 'WORKS!'], 200);
	}

	// Fnugg Suggestions/AutoComplete
	public static function fnuggSuggest(WP_REST_Request $request)
	{
		$data = [];
		$q = urlencode($request->get_param('q'));
		$transitentKey = 'fnugg_cache_suggest_' . $q;

		// Check if "q" is in cache
		$cache = get_transient($transitentKey);

		if ($cache) {
			$result = new WP_REST_Response($cache, 200);

			return $result;
		}

		// If not in cache, get from fnugg and cache it
		$response = wp_safe_remote_get(FNUGG_URL . "/suggest/autocomplete?q=$q");


		if (isset($response['body'])) {
			$data = json_decode($response['body'], true);
			$data = isset($data["result"]) ? array_column($data["result"], "name") ?? [] : [];
		}

		if (!$data) {
			return new WP_Error('fnugg_error', 'Nothing found.', array('status' => 200)); //200 because console error is annoying.
		}

		// Cache the result
		set_transient($transitentKey, $data, CACHE_TIME);


		$result = new WP_REST_Response($data, 200);

		// Set headers.
		return $result;
	}
	// Fnugg Search
	public static function fnuggSearch(WP_REST_Request $request)
	{
		$data = [];
		$q = urlencode($request->get_param('q'));
		$transitentKey = 'fnugg_cache_search_' . $q;
		$sourceFields = 'name,images.image_1_1_s,conditions.forecast.today.top,slopes.open';

		// Check if "q" is in cache
		$cache = get_transient($transitentKey);

		if ($cache) {
			$result = new WP_REST_Response($cache, 200);

			return $result;
		}

		// If not in cache, get from fnugg and cache it
		$response = wp_remote_get(FNUGG_URL . '/search?q=' . $q . '&sourceFields=' . $sourceFields);

		$data = json_decode($response['body'], true);

		if (isset($data["hits"]["hits"][0]["_source"])) {
			$source = $data["hits"]["hits"][0]["_source"];
			$conditions = $source["conditions"]["forecast"]["today"]["top"];
			$resort = [
				"name" => $source["name"],
				"image" => $source["images"]["image_1_1_s"],
				"last_updated" => date("d.m.Y - h:i", strtotime($conditions["last_updated"])),
				"conditionIcon" => $conditions["symbol"]['fnugg_id'],
				"condition" => $conditions["condition_description"],
				"wind" => $conditions["wind"],
				"temperature" => $conditions["temperature"],
				"slopes" => $source["slopes"]["open"],
			];

			$data = $resort;
		}

		if (!$data) {
			// This should never happen, but just in case...
			return new WP_Error('fnugg_error', 'Nothing found.', array('status' => 500));
		}



		// Cache the result
		set_transient($transitentKey, $data, CACHE_TIME);


		$result = new WP_REST_Response($data, 200);

		// // Set headers.
		// $result->set_headers(array('Cache-Control' => 'max-age=3600'));

		return $result;
	}


	/**
	 * class constructor
	 */
	public function __construct()
	{
	}

	/**
	 * Initializes WordPress hooks
	 */
	public function init()
	{
	}
}
