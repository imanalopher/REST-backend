<?php
namespace Connection;
class Connection {
	public static function get_connection_string($mode) {
		$parser = new \Symfony\Component\Yaml\Parser();
		$db_config = $parser->parse(file_get_contents('phinx.yml'));
		$host = $db_config['environments'][$mode]['host'];
		$user = $db_config['environments'][$mode]['user'];
		$password = $db_config['environments'][$mode]['pass'];
		$name = $db_config['environments'][$mode]['name'];
		$adapter = $db_config['environments'][$mode]['adapter'];
		return $adapter . "://" . $user . ":" . $password . "@" . $host . "/" . $name;
	}
}
