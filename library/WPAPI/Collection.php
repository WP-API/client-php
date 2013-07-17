<?php
/**
 * Collection base interface
 *
 * @package WordPress API Client
 * @subpackage Entities
 */

/**
 * Collection base interface
 *
 * @package WordPress API Client
 * @subpackage Entities
 */
interface WPAPI_Collection {
	public function getAll();
	public function get($id);
}