<?php

class Database
{
    private $connection;
    private static $instance;

    //TODO put data to config file
    private $host = "127.0.0.1";
    private $username = "root";
    private $password = "";
    private $database = "testDB";

    /*
    Get an instance of the Database
    @return Instance
    */
    public static function getInstance()
    {
        if (!self::$instance) { // If no instance then make one
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Constructor
    private function __construct()
    {
        try {
            $this->connection = new \PDO("mysql:host=$this->host;dbname=$this->database", $this->username, $this->password);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    // Return mysql pdo connection
    public function getConnection()
    {
        return $this->connection;
    }
}
