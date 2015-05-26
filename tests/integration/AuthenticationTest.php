<?php

class AuthenticationTest extends LocalWebTestCase {
	const AUTH_PASS = true;
	const AUTH_FAIL = false;

	public function setUp() {
		parent::setUp();
		$this->connection = ActiveRecord\ConnectionManager::get_connection();
		$this->connection->transaction();
	}
	public function tearDown() {
		$this->connection->rollback();
		return parent::tearDown();
	}
	public function testLogin() {
		\User::create(array('username' => "nicholasjstock", "email" => "stockn@gmail.com", "first_name" => "nicholas", "last_name" => "stock", "password" => "surfer"));
		$parameters = array('user' => array('username' => 'nicholasjstock', "password" => "surfer"));
		$this->client->post('/users/sign_in', $parameters);
		$this->assertEquals(200, $this->client->response->status());
	}
	public function testProtectedRoute() {
		\User::create(array('auth_token' => "thoken", 'username' => "nicholasjstock", "email" => "stockn@gmail.com", "first_name" => "nicholas", "last_name" => "stock", "password" => "surfer"));
		$header = 'Token token="thoken", user_email="stockn@gmail.com"';
		$this->client->get("/api/speakers", array(), array("HTTP_Authorization" => $header));
		$this->assertEquals(200, $this->client->response->status());
	}
	public function testProtectedRouteWrongCredentials() {
		$header = 'Token token="thoken", user_email="stockn@gmail.com"';
		$this->client->get("/api/speakers", array(), array("HTTP_Authorization" => $header));
		$this->assertEquals(403, $this->client->response->status());
	}

	public function testProtectedRouteNotLoggedIn() {
		$this->client->get("/api/speakers");
		$this->assertEquals(403, $this->client->response->status());

	}

}

/* End of file FileStoreTest.php */
