<?php

namespace Torm;

class Database {
	const MySQL = 'mysql';
	const SQLite = 'sqlite';

	private static $hostname = 'localhost';
	private static $username = 'root';
	private static $password = '';
	private static $database = 'mysql';
	private static $type = 'mysql';

	public static function configure($type, $hostname, $username = null, $password = null, $database = null) {
		static::$type = $type;
		static::$hostname = $hostname;
		static::$username = $username;
		static::$password = $password;
		static::$database = $database;
	}

	public static function getHandle() {
		$db = null;

		switch (static::$type) {
			case 'mysql':
				$db = new \PDO('mysql:host=' . static::$hostname . ';dbname=' . static::$database, static::$username, static::$password, array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES "UTF8"'));
				break;
			case 'pgsql':
				$db = new \PDO('pgsql:host=' . static::$hostname . ';dbname=' . static::$database, static::$username, static::$password);
				break;
			case 'sqlite':
				$db = new \PDO('sqlite:' . static::$hostname);
				break;
		}

		return $db;
	}

	public static function sanitize($text) {
		return '`' . str_replace(['`', ';'], '', $text) . '`';
	}
}
