<?php
/**
 * Hello!
 *
 * @package Requests
 */

class Requests_Response_CookieJar implements ArrayAccess, IteratorAggregate {
	/**
	 * Actual header data
	 *
	 * @var array
	 */
	protected $data = array();

	/**
	 * Check if the given header exists
	 *
	 * @param string $key
	 * @return boolean
	 */
	public function offsetExists($key) {
		return isset($this->data[$key]);
	}

	/**
	 * Get the given cookie
	 *
	 * @param string $key
	 * @return string Header value
	 */
	public function offsetGet($key) {
		return isset($this->data[$key]) ? $this->data[$key] : null;
	}

	/**
	 * Set the given header
	 *
	 * @throws Requests_Exception On attempting to use headers dictionary as list (`invalidset`)
	 *
	 * @param string $key Header name
	 * @param string $value Header value
	 */
	public function offsetSet($key, $value) {
		if ($key === null) {
			throw new Requests_Exception('Headers is a dictionary, not a list', 'invalidset');
		}

		$this->data[$key] = $value;
	}

	/**
	 * Unset the given header
	 *
	 * @param string $key
	 */
	public function offsetUnset($key) {
		unset($this->data[$key]);
	}

	/**
	 * Get an interator for the data
	 *
	 * @return ArrayIterator
	 */
	public function getIterator() {
		return new ArrayIterator($this->data);
	}
}