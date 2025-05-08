<?php

class Database {
    private $host = 'sistema-dev.avantecds.es';
    private $dbname = 'log';
    private $username = 'sistema';
    private $password = '3eQFHxWhTTGavMmcYNYe';
    public $conn;

    public function connect() {
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database Connection Error: " . $e->getMessage());
        }
        return $this->conn;
    }
}
