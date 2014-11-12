<?php
// Settings to make all errors more obvious during testing
error_reporting(-1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
date_default_timezone_set('America/New_York');

use There4\Slim\Test\WebTestCase;

define('PROJECT_ROOT', realpath(__DIR__ . '/..'));

ob_start();
//require __DIR__.'/bootstrap.php.cache';

$mode = "testing";
require_once PROJECT_ROOT . '/vendor/autoload.php';
require_once PROJECT_ROOT . '/vendor/php-activerecord/php-activerecord/ActiveRecord.php';
require 'lib/Auth/Auth.php';
require 'app/midleware/ActiveRecordMidleware.php';

// Initialize our own copy of the slim application
class LocalWebTestCase extends WebTestCase {
	public function setUp() {
		parent::setUp();
	}

	public function tearDown() {

	}
	public function getSlimInstance() {
		$app = new \Slim\Slim(array(
			'version' => '0.0.0',
			'debug' => true,
			'mode' => 'testing',
			'templates.path' => __DIR__ . '/../app/templates',
		));

		// Include our core application file
		require PROJECT_ROOT . '/app/app.php';
		return $app;
	}
};

/* End of file bootstrap.php */
