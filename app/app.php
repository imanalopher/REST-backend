<?php
//
// SlimPHP Example Application
// =============================================================================
//
// This is a small application with a few endpoints that have been designed to
// highlight some common integration testing techniques. This application has
// objects for external requests via Curl, and a custom authentication class.
// We'll be able to mock both of those in order to control our application
// state, and we've got some simple behaviors to test in the endpoint for
// authenticated file storage access.
//
// Please view the `README.md` file, and check out the integration tests in
// `tests/integration/`.
//
// * Author: [Craig Davis](craig@there4development.com)
// * Since: 10/2/2013
//
// -----------------------------------------------------------------------------

// Dependency Injection Containers
// -----------------------------------------------------------------------------
// In our unit tests, we'll mock these so that we can control our application
// state.
use Auth\Auth;

$app->curl = function ($c) use ($app) {
	return new \Curl();
};

$app->authentication = function ($c) use ($app) {
	return new Auth($app);
};
new ActiveRecordMidleware\Connect();

// $app->hook('slim.before', function () use ($app) {
// 	// Do something
// });

// Twig View Rendering
// -----------------------------------------------------------------------------
// Setup our renderer and add some global variables
$app->view(new \Slim\Views\Twig());
$app->view->parserOptions = array(
	'charset' => 'utf-8',
	'cache' => false,
	'auto_reload' => true,
	'strict_variables' => true,
	'autoescape' => true
);
$app->view->parserExtensions = array(new \Slim\Views\TwigExtension());

// Token Authentication Middleware
// -----------------------------------------------------------------------------
// Halt the response if the token is not valid.
$authenticate = function ($app) {
	return function () use ($app) {
		$auth = $app->authentication;
		if ($auth->current_session()) {
			return;
		}
		$app->halt(403, json_encode(["error" => "Not valid"]));
	};
};

// Error Handler for any uncaught exception
// -----------------------------------------------------------------------------
// This can be silenced by turning on Slim Debugging. All exceptions thrown by
// our application will be collected here.
$app->error(function (\Exception $e) use ($app) {
	$app->render('error.html', array(
		'message' => $e->getMessage()
	), 500);
});

$app->get('/phpinfo', function () use ($app) {
	phpinfo();
});
$app->post('/users/sign_in', function () use ($app) {
	$user = $app->request->post("user");

	if (in_array("username", array_keys($user))) {
		$usernameOrEmail = $user['username'];

	} else {
		$usernameOrEmail = $user['email'];
	}
	$login = $app->authentication->login($usernameOrEmail, $user['password']);
	if ($login) {
		$app->response->write(json_encode($app->authentication->current_session()));
	} else {
		$app->halt(403, json_encode(["error" => "Not valid"]));
	}
});

$app->get('/api/speakers', $authenticate($app), function () use ($app) {
	$name = $app->request->params('name');
	$response = $name ? 'Hello ' . $name : 'Missing parameter for name';
	$app->response->write($response);
});

/* End of file app.php */