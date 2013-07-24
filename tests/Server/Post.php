<?php

class WPAPI_Test_Server_Post extends WPAPI_Test_Server {
	public function setUp() {
		parent::setUp();

		$this->post = $this->api->posts->create(array(
			'title' => 'API Test Post',
			'content_raw' => 'This is a test to check that creating posts from the API works correctly.',
			'excerpt_raw' => 'Test post from the API',
			'date' => date('c', time())
		));

		$this->assertInstanceOf('WPAPI_Post', $this->post);
	}

	public function testAgainstSchema() {
		$this->checkEntityAgainstSchema($this->post->getRawData(), 'post');
	}

	public function testProperties() {
		$this->assertNotEmpty($this->post->title);
		$this->assertEquals('API Test Post', $this->post->title);

		$this->assertNotEmpty($this->post->date);
		$this->assertNotEmpty($this->post->date_gmt);
		$this->assertNotEmpty($this->post->date_tz);
	}

	public function testUpdateTitle() {
		$success = $this->post->update(array(
			'title' => 'API Test Post Updated'
		));
		$this->assertTrue($success);
		$this->assertEquals('API Test Post Updated', $this->post->title);
	}

	public function testUpdateContent() {
		$success = $this->post->update(array(
			'content_raw' => 'This is a test of updating the post.',
		));
		$this->assertTrue($success);
		$this->assertEquals('This is a test of updating the post.', $this->post->content_raw);
	}

	/**
	 * @depends testProperties
	 */
	public function testTimezoneFormat() {
		$timezones = DateTimeZone::listIdentifiers();
		$this->assertContains($this->post->date_tz, $timezones);
	}

	public function testIgnoreTimezoneForGMT() {
		$date = gmdate('c', strtotime('-1 day'));

		$success = $this->post->update(array(
			'date_gmt' => str_replace('+00:00', '+9:00', $date)
		));
		$this->assertTrue($success);

		$this->assertEquals($date, $this->post->date_gmt);
	}

	public function tearDown() {
		$id = $this->post->ID;
		$result = $this->post->delete();
		$this->assertTrue($result);

		// Check that the post is gone
		try {
			$post = $this->api->posts->get($id);
		}
		catch (Requests_Exception $e) {
			if ($e->getCode() === 404)
				return;
		}

		$this->fail("Post still appears to exist");
	}
}