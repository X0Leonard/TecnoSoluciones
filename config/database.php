<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '');
}

class Database {
    private static $instance = null;
    private static $host     = 'mysql.railway.internal';
    private static $dbname   = 'railway';
    private static $user     = 'root';
    private static $password = 'TpqDbwCpEhpValilioSRsIifQvVyPkdf';
    private static $port     = '3306';

    private function __construct() {}

    public static function getConnection() {
        if (self::$instance === null) {
            try {
                self::$instance = new PDO(
                    'mysql:host=' . self::$host . ';port=' . self::$port . ';dbname=' . self::$dbname . ';charset=utf8',
                    self::$user,
                    self::$password
                );
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die('Connection error: ' . $e->getMessage());
            }
        }
        return self::$instance;
    }
}