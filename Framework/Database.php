<?php
/**
 * Database Access Class
 *
 * Provides the database access tools used by our micro-framework
 * Filename:        Database.php
 * Location:        Framework/
 * Project:         MA-SaaS-Vanilla-MVC
 * Date Created:    19/03/2025
 *
 * Author:          Martina Ait
 *
 */

namespace Framework;
use Exception;
use PDO;
use PDOStatement;
use PDOException;

class Database
{
    /**
     * Connection Property
     *
     * @var PDO
     */
    public $conn;
    /**
     * Constructor for Database class
     *
     * @param array $config
     */
    public function __construct($config)
    {
        $host =$config['host'];
        $port = $config['port'];
        $dbName = $config['dbname'];

        $dsn = "mysql:host={$host};port={$port};dbname={$dbName}";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        ];

        try {
            $this->conn = new PDO($dsn, $config['username'], $config['password'], $options);
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: {$e->getMessage()}");
        }
    }
    /**
     * Query the database
     */
    public function query($query, $params = [])
    {
        try {
            $sth = $this->conn->prepare($query);

            // Bind named params
            foreach ($params as $param => $value) {
                $sth->bindValue(':' . $param, $value);
            }

            $sth->execute();
            return $sth;
        } catch (PDOException $e) {
            throw new Exception("Query failed to execute: {$e->getMessage()}");
        }
    }
}
