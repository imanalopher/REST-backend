<?php
//
// SlimPHP Example Application
// =============================================================================
//
// This is the public entrance into our application. It sets up a production
// SlimPHP environment, and runs the app.
//
// * Author: [Craig Davis](craig@there4development.com)
// * Since: 10/2/2013
//
// -----------------------------------------------------------------------------
require_once '../vendor/autoload.php';
require_once '../vendor/php-activerecord/php-activerecord/ActiveRecord.php';
require 'lib/Auth/Auth.php';
require 'app/midleware/ActiveRecordMidleware.php';

$composer = json_decode(file_get_contents(__DIR__ . '/../composer.json'));

$app = new \Slim\Slim(
	array(
		'version' => $composer->version,
		'debug' => true,
		'mode' => 'development',
		'templates.path' => __DIR__ . '/../app/templates',
	)
);

require_once __DIR__ . '/../app/app.php';

$app->run();

/* End of file index.php */
