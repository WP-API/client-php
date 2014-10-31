<?php
/**
 * API client for the WordPress JSON REST API
 *
 * @package WordPress API Client
 */

/**
 * API client for the WordPress JSON REST API
 *
 * @package WordPress API Client
 */
class WPAPI {
	/**
	 * API base URL
	 *
	 * @var string
	 */
	public $base = '';

	/**
	 * Available collections
	 *
	 * @var array
	 */
	protected $collections = array();

	/**
	 * Authentication bits
	 * @var array
	 */
	protected $auth = array();

	// This is very un-HATEOAS, but it also means one less request
	const ROUTE_INDEX = '/';
	const ROUTE_POSTS = '/posts';
	const ROUTE_POST = '/posts/%d';
	const ROUTE_MEDIA = '/media';
	const ROUTE_MEDIA_SINGLE = '/media/%d';
	const ROUTE_USERS = '/users';
	const ROUTE_USER = '/users/%d';
	const ROUTE_USER_CURRENT = '/users/me';

	/**
	 * Constructor
	 * @param string $base Base URL for the API
	 * @param string|null $username Username to connect as, empty to skip authentication
	 * @param string|null $password Password for the user
	 */
	public function __construct($base, $username = null, $password = null) {
		$this->base = $base;

		if ( ! empty( $username ) ) {
			$this->auth = array($username, $password);
		}
	}

	/**
	 * Autoload a WPAPI class
	 *
	 * @param string $class Class name
	 */
	public static function autoloader($class) {
		// Check that the class starts with "Requests"
		if (strpos($class, 'WPAPI') !== 0) {
			return;
		}

		$file = str_replace('_', '/', $class);
		if (file_exists(dirname(__FILE__) . '/' . $file . '.php')) {
			require_once(dirname(__FILE__) . '/' . $file . '.php');
		}
	}

	/**
	 * Register the standard WPAPI autoloader
	 */
	public static function register_autoloader() {
		spl_autoload_register(array('WPAPI', 'autoloader'));
	}

	/**
	 * Get a collection
	 *
	 * @throws OutOfRangeException Invalid key
	 * @param string $key Key of the collection to get
	 * @return mixed Collection object
	 */
	public function __get($key) {
		$classes = array(
			'index' => 'WPAPI_Index',
			'posts' => 'WPAPI_Posts',
			'users' => 'WPAPI_Users',
		);

		if (!isset($classes[$key])) {
			throw new OutOfRangeException('Key not found');
		}

		if (!isset($this->collections[$key])) {
			$this->collections[$key] = new $classes[$key]($this);
		}

		return $this->collections[$key];
	}

	/**
	 * Get the default Requests options
	 *
	 * @return array Options to pass to Requests
	 */
	public function getDefaultOptions() {
		$options = array();
		if ( ! empty( $this->auth ) )
			$options['auth'] = $this->auth;

		return $options;
	}

	/**
	 * Set authentication parameter
	 */
	public function setAuth( $auth ) {
		$this->auth = $auth;
	}

	/**#@+
	 * Requests proxy functions
	 *
	 * These take relative URLs instead of absolute, and ensure that
	 * authentication is added to endpoints as needed.
	 */
	/**
	 * Send a GET request
	 */
	public function get($endpoint, $headers = array(), $data = array(), $options = array()) {
		return $this->request($endpoint, $headers, $data, Requests::GET, $options);
	}
	/**
	 * Send a HEAD request
	 */
	public function head($endpoint, $headers = array(), $options = array()) {
		return $this->request($endpoint, $headers, array(), Requests::HEAD, $options);
	}
	/**
	 * Send a DELETE request
	 */
	public function delete($endpoint, $headers = array(), $options = array()) {
		return $this->request($endpoint, $headers, array(), Requests::DELETE, $options);
	}

	/**
	 * Send a POST request
	 */
	public function post($endpoint, $headers, $data = array(), $options = array()) {
		return $this->request($endpoint, $headers, $data, Requests::POST, $options);
	}
	/**
	 * Send a PUT request
	 */
	public function put($endpoint, $headers, $data = array(), $options = array()) {
		return $this->request($endpoint, $headers, $data, Requests::PUT, $options);
	}
	/**
	 * Send a PUT request
	 */
	public function patch($endpoint, $headers, $data = array(), $options = array()) {
		return $this->request($endpoint, $headers, $data, Requests::PUT, $options);
	}

	/**
	 * Send a HTTP request
	 */
	public function request($endpoint, $headers = array(), $data = array(), $type = Requests::GET, $options = array()) {
		$url = $this->base . $endpoint;
		$options = array_merge($this->getDefaultOptions(), $options);
		return Requests::request($url, $headers, $data, $type, $options);
	}
	/**#@-*/
}