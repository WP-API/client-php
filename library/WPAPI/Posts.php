<?php
/**
 * Posts collection
 *
 * @package WordPress API Client
 * @subpackage Collections
 */

/**
 * Posts collection
 *
 * @package WordPress API Client
 * @subpackage Collections
 */
class WPAPI_Posts implements WPAPI_Collection {
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
		$response = $this->api->get(WPAPI::ROUTE_POSTS);
		$posts = json_decode($response->body, true);
		foreach ($posts as &$post) {
			$post = new WPAPI_Post($this->api, $post);
		}
		return $posts;
	}

	/**
	 * Get a single post
	 *
	 * @throws Requests_Exception Failed to retrieve the post
	 * @throws Exception Failed to decode JSON
	 * @param int $id Post ID
	 * @return WPAPI_Post
	 */
	public function get($id) {
		$url = sprintf( WPAPI::ROUTE_POST, $id );
		$response = $this->api->get($url);
		$response->throw_for_status();

		$data = json_decode($response->body, true);

		$has_error = ( function_exists('json_last_error') && json_last_error() !== JSON_ERROR_NONE );
		if ( ( ! $has_error && $data === null ) || $has_error ) {
			throw new Exception($response->body);
		}

		return new WPAPI_Post($this->api, $data);
	}

	/**
	 * Create a new post
	 *
	 * @throws Requests_Exception Failed to retrieve the post
	 * @throws Exception Failed to decode JSON
	 * @param array $data Post data to create
	 * @return WPAPI_Post
	 */
	public function create($data) {
		$data = json_encode($data);
		$headers = array('Content-Type' => 'application/json');
		$response = $this->api->post(WPAPI::ROUTE_POSTS, $headers, $data);
		$response->throw_for_status();

		$data = json_decode($response->body, true);
		$has_error = ( function_exists('json_last_error') && json_last_error() !== JSON_ERROR_NONE );
		if ( ( ! $has_error && $data === null ) || $has_error ) {
			throw new Exception($response->body);
		}
		return new WPAPI_Post($this->api, $data);
	}
}