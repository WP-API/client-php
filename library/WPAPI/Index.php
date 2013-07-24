<?php
/**
 * Index entity object
 *
 * @package WordPress API Client
 * @subpackage Entities
 */

/**
 * Index entity object
 *
 * @package WordPress API Client
 * @subpackage Entities
 */
class WPAPI_Index {
	/**
	 * API object
	 *
	 * @var WPAPI
	 */
	protected $api;

	/**
	 * Constructor
	 *
	 * @param WPAPI $api API handler object
	 */
	public function __construct($api) {
		$this->api = $api;

		$this->fetch();
	}

	/**
	 * Get a property from the index
	 *
	 * @param string $key Property name to get
	 * @return mixed Property value
	 */
	public function __get($key) {
		return $this->data[$key];
	}

	/**
	 * Get the raw internal post data
	 *
	 * Avoid use in favour of accessing via the properties instead.
	 *
	 * @return array Raw data from the API
	 */
	public function getRawData() {
		return $this->data;
	}

	/**
	 * Fetch the index data
	 *
	 * @throws Requests_Exception Failed to retrieve the index
	 * @throws Exception Failed to decode JSON
	 * @return boolean Was it successful?
	 */
	public function fetch() {
		$response = $this->api->get(WPAPI::ROUTE_INDEX);
		$response->throw_for_status();

		$data = json_decode($response->body, true);

		$has_error = ( function_exists('json_last_error') && json_last_error() !== JSON_ERROR_NONE );
		if ( ( ! $has_error && $data === null ) || $has_error ) {
			throw new Exception($response->body);
		}

		$this->data = $data;
		return true;
	}

	/**
	 * Check if a route exists
	 *
	 * @param string $route Route name to check
	 * @return boolean
	 */
	public function hasRoute($route) {
		$routes = $this->routes;
		return ! empty( $routes[ $route ] );
	}
}