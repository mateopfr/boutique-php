<?php
class Database
{
    private function __construct() {}

    private static ?PDO $instance = null;
    
    public static function getConnection(): PDO
    {
        // TODO : Set un mot de passe
        [
            'HOST' => $dbHost,
            'DB_NAME' => $dbName,
            'CHARSET' => $dbCharset,
            'USER' => $dbUser
        ] = parse_ini_file(__DIR__ . '/../config/db.ini');
        
        $dsn = "mysql:host=$dbHost;dbname=$dbName;charset=$dbCharset";

        if (self::$instance === null) {
            self::$instance = new PDO($dsn, $dbUser);
        }
        
        return self::$instance;
    }
}