<?php
/**
 * Collection base interface
 *
 * @package WordPress API Client
 * @subpackage Collections
 */

/**
 * Collection base interface
 *
 * @package WordPress API Client
 * @subpackage Collections
 */
interface WPAPI_Collection {
	public function getAll();
	public function get($id);
}