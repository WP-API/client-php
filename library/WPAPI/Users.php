<?php

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
	 * Get all posts
	 *
	 * @return array List of WPAPI_Post objects
	 */
	public function getAll() {

		$response = $this->api->get( WPAPI::ROUTE_USERS );
		$users = json_decode( $response->body, true );
		foreach ( $users as &$user ) {
			$user = new WPAPI_User( $this->api, $user );
		}
		return $users;
	}

}