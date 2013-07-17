<?php

class WPAPI_Test_Server_Posts {
	public function setUp() {
		parent::setUp();

		$this->posts = $this->api->posts;
	}

	public function testCollection() {
		$posts = $this->posts->getAll();
		$this->assertTrue(is_array($posts));
		$this->assertNotEmpty($posts);
	}
}