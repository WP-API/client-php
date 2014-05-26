<?php
/**
 * Users collection
 *
 * @package WordPress API Client
 * @subpackage Collections
 */

/**
 * Users collection
 *
 * @package WordPress API Client
 * @subpackage Collections
 */
class WPAPI_Users implements WPAPI_Collection {

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
	}

	/**
	 * Get all users
	 *
	 * @return array List of WPAPI_User objects
	 */
	public function getAll() {
		$response = $this->api->get( WPAPI::ROUTE_USERS );
		$users = json_decode( $response->body, true );
		foreach ( $users as &$user ) {
			$user = new WPAPI_User( $this->api, $user );
		}
		return $users;
	}

	/**
	 * Get a single user
	 *
	 * @throws Requests_Exception Failed to retrieve the user
	 * @throws Exception Failed to decode JSON
	 * @param int $id User ID
	 * @return WPAPI_User
	 */
	public function get($id) {
		$url = sprintf( WPAPI::ROUTE_USER, $id );
		$response = $this->api->get( $url );
		$response->throw_for_status();

		$data = json_decode( $response->body, true );

		$has_error = ( function_exists('json_last_error') && json_last_error() !== JSON_ERROR_NONE );
		if ( ( ! $has_error && $data === null ) || $has_error ) {
			throw new Exception( $response->body );
		}

		return new WPAPI_User( $this->api, $data );
	}

	/**
	 * Create a new user
	 *
	 * @throws Requests_Exception Failed to retrieve the user
	 * @throws Exception Failed to decode JSON
	 * @param array $data User data to create
	 * @return WPAPI_User
	 */
	public function create( $data ) {
		$data = json_encode( $data );
		$headers = array( 'Content-Type' => 'application/json' );
		$response = $this->api->post( WPAPI::ROUTE_USERS, $headers, $data );
		$response->throw_for_status();

		$data = json_decode( $response->body, true );
		$has_error = ( function_exists('json_last_error') && json_last_error() !== JSON_ERROR_NONE );
		if ( ( ! $has_error && $data === null ) || $has_error ) {
			throw new Exception( $response->body );
		}
		return new WPAPI_User( $this->api, $data );
	}
}
