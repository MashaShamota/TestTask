<?php
require './configs/configs.php';

class Database
{
    /**
     * Property: connection
     * DB connection
     */
    private $connection;

    /**
     * Property: $instance
     * DB instance
     */
    private static $instance;


    private $host = DB_HOST;
    private $username = DB_USER;
    private $password = DB_PASSWORD;
    private $database = DB_NAME;

    /**
     * Database constructor.
     */
    public function __construct()
    {
        try {
            $this->connection = new \PDO("mysql:host=$this->host;dbname=$this->database", $this->username, $this->password);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Get an instance of database
     * @return Database
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @return PDO connection
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
