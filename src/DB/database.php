<?php

class DataBase {
    public $conn;

    public function __construct() {
        $this->conn = new mysqli('localhost', 'root', '', 'e_learning');
        if ($this->conn->connect_error) {
            throw new Exception("Connection failed: " . $this->conn->connect_error);
        }
    }
}

?>
