<?php

use JsonSchema\Uri\UriRetriever;
use JsonSchema\Validator;

class WPAPI_Test_Server extends PHPUnit_Framework_TestCase {
	protected $api;

	/**
	 * Predefined schema retriever
	 *
	 * @var JsonSchema\Uri\UriRetriever
	 */
	protected $retriever;

	/**
	 * The current schema base URL
	 */
	const SCHEMA_BASE = 'http://gsoc.svn.wordpress.org/2013/rmccue/trunk/docs/schema.json#';

	public function setUp() {
		global $testconfig;
		$this->api = new WPAPI($testconfig['url'], $testconfig['username'], $testconfig['password']);

		// Set up the schema retriever. This should be included instead,
		// once finalised.
		$schema_data = Requests::get(self::SCHEMA_BASE);

		$arrayretriever = new WPAPI_Test_SchemaRetriever(array(
			self::SCHEMA_BASE => $schema_data->body,
		));

		$this->retriever = new UriRetriever();
		$this->retriever->setUriRetriever( $arrayretriever );
	}

	/**
	 * Check data against a given entity type from the schema
	 *
	 * @param array|object $data Data from the API. Associative arrays will be reparsed into objects
	 * @param string $entity Entity type, from the schema
	 */
	protected function checkEntityAgainstSchema($data, $entity) {
		$absolute_ref = self::SCHEMA_BASE . 'definitions/' . $entity;

		$schema = $this->retriever->retrieve($absolute_ref);

		if (is_array($data)) {
			// Data was decoded as an array instead of an object, reencode for
			// schema checking
			$data = json_decode(json_encode($data));
		}

		$validator = new Validator(Validator::CHECK_MODE_NORMAL, $this->retriever);
		$validator->check($data, $schema);

		if ( ! $validator->isValid() ) {
			$message = "JSON does not validate against schema:\n";
			$i = 0;
			foreach ($validator->getErrors() as $error) {
				$i++;
				$message .= $i . ') ';

				if (!empty($error['property']))
					$message .= sprintf("[%s] %s\n", $error['property'], $error['message']);
				else
					$message .= $error['message'] . "\n";
			}
			$this->fail($message);
		}
	}
}