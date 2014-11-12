<?php
namespace ActiveRecordMidleware;
include './app/Connection/Connection.php';
class Connect {
	public function __construct() {

		\ActiveRecord\Config::initialize(function ($cfg) {
			$cfg->set_model_directory('app/models');
			$mode = \Slim\Slim::getInstance()->mode;
			$connection_string = \Connection\Connection::get_connection_string($mode);
			$cfg->set_connections(array(
				"development" => $connection_string,
			));
		});

	}
}