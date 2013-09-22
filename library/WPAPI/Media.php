<?php
/**
 * Media collection
 *
 * @package WordPress API Client
 * @subpackage Collections
 */

/**
 * Media collection
 *
 * @package WordPress API Client
 * @subpackage Collections
 */
class WPAPI_Media extends WPAPI_Posts implements WPAPI_Collection {
	/**
	 * Create a new post
	 *
	 * @throws Requests_Exception Failed to retrieve the post
	 * @throws Exception Failed to decode JSON
	 * @param array $data Post data to create
	 * @return WPAPI_Post
	 */
	public function create($data) {
		$headers = array('Content-Type' => 'application/octet-stream');
		$response = $this->api->post(WPAPI::ROUTE_MEDIA, $headers, $data);
		$response->throw_for_status();

		$data = json_decode($response->body, true);
		$has_error = ( function_exists('json_last_error') && json_last_error() !== JSON_ERROR_NONE );
		if ( ( ! $has_error && $data === null ) || $has_error ) {
			throw new Exception($response->body);
		}
		return new WPAPI_Post($this->api, $data);
	}
}