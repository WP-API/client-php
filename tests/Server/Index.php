<?php

class WPAPI_Test_Server_Index extends WPAPI_Test_Server {
	public function setUp() {
		parent::setUp();

		$this->index = $this->api->index;
	}

	public function testAgainstSchema() {
		$this->checkEntityAgainstSchema($this->index->getRawData(), 'index');
	}

	public function testBasicInformation() {
		$this->assertNotEmpty($this->index->name);
		$this->assertNotEmpty($this->index->description);

		$this->assertNotEmpty($this->index->routes);
		$this->assertTrue($this->index->hasRoute('/'));

		foreach ($this->index->routes as $route) {
			$this->assertNotEmpty($route['supports']);
		}

		$this->assertNotEmpty($this->index->meta);
		$this->assertNotEmpty($this->index->meta['links']);
		$this->assertNotEmpty($this->index->meta['links']['help']);
	}

	/**
	 * @depends testBasicInformation
	 */
	public function testPostRoutes() {
		$this->assertTrue($this->index->hasRoute('/posts'));
		$this->assertTrue($this->index->hasRoute('/posts/<id>'));
	}
}