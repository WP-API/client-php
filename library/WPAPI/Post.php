<?php
/**
 * Post entity object
 *
 * @package WordPress API Client
 * @subpackage Entities
 */

/**
 * Post entity object
 *
 * @package WordPress API Client
 * @subpackage Entities
 */
class WPAPI_Post {
	/**
	 * API handler
	 *
	 * @var WPAPI
	 */
	protected $api;

	/**
	 * Data container
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * Keys that have been changed since last update
	 *
	 * @var array
	 */
	protected $changed = array();

	/**
	 * Constructor
	 *
	 * @param WPAPI $api API object
	 * @param array $data Data to initialise the object with
	 */
	public function __construct($api, $data = array()) {
		$this->api = $api;
		$this->data = (array) $data;
	}

	/**
	 * Get a property
	 *
	 * See the specification for data keys/values returned by the API.
	 *
	 * @param string $key Key to retrieve
	 * @return mixed Post value for the key
	 */
	public function __get($key) {
		if (!isset($this->data[$key])) {
			return null;
		}
		return $this->data[$key];
	}

	/**
	 * Set a property
	 *
	 * @param string $key Key to replace
	 * @param mixed $value Value for the key
	 */
	public function __set($key, $value) {
		$this->data[$key] = $value;
		$this->changed[$key] = true;
	}

	/**
	 * Update the post
	 *
	 * @throws Requests_Exception Failed to update the post
	 * @throws Exception Failed to decode JSON
	 * @param array $data Data to update, in addition to already changed data
	 * @param boolean $use_json Use a JSON body rather than form-encoded data
	 * @param boolean $check_modification Should we change the last modified date? Avoids editing conflicts
	 * @return boolean Was the update successful?
	 */
	public function update($data = array(), $use_json = true, $check_modification = true) {
		$keys = array_keys( $this->changed );
		$values = array();

		if ( ! empty( $data ) ) {
			$values = array_merge( $values, array_diff_assoc($data, $this->data) );
		}

		// Don't send the ID with the data
		unset($values['ID']);

		$headers = array();

		if ($use_json) {
			$body = json_encode($values);
			$headers['Content-Type'] = 'application/json';
		}
		else {
			$body = array(
				'data' => $values,
			);
		}

		if ($check_modification) {
			$headers['If-Unmodified-Since'] = date( DateTime::RFC1123, strtotime( $this->modified ) );
		}
		$response = Requests::put($this->meta['links']['self'], $headers, $body, $this->api->getDefaultOptions());
		$response->throw_for_status();

		$data = json_decode($response->body, true);
		$this->data = $data['data'];
		$this->changed = array();

		return true;
	}

	/**
	 * Delete the post
	 *
	 * @throws Requests_Exception Failed to delete post
	 * @return boolean Was the deletion successful?
	 */
	public function delete($permanent = false) {
		$url = $this->meta['links']['self'];
		if ($permanent) {
			if (strpos($url, '?') !== false) {
				$url .= '&force=true';
			}
			else {
				$url .= '?force=true';
			}
		}
		$response = Requests::delete($url, array(), $this->api->getDefaultOptions());
		$response->throw_for_status();

		$this->data = array();
		$this->changed = array();
		return true;
	}
}