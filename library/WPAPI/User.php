<?php

/**
 * User entity object
 *
 * @package WordPress API Client
 * @subpackage Entities
 */
class WPAPI_User {
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
	 * @return mixed User value for the key
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

}