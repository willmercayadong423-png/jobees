<?php

 namespace Framework;
 use PDO;
use PDOException;

class Database
{
    public PDO $conn;

    public function __construct($config)
    {
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        ];

        try {
            $this->conn = new PDO(
                $dsn,
                $config['username'],
                $config['password'],
                $options // ✅ apply options
            );
        } catch (PDOException $e) {
           throw new \Exception("Database connection failed: " . $e->getMessage());
        }
    }

    // ✅ Add your query method here (you were missing it in this class)
    public function query($query, $params = [])
{
    try {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        throw new \Exception("Query failed: " . $e->getMessage());
    }
}
}