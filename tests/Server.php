<?php

class WPAPI_Test_Server extends PHPUnit_Framework_TestCase {
	public function setUp() {
		global $testconfig;
		$this->api = new WPAPI($testconfig['url'], $testconfig['username'], $testconfig['password']);
	}
}