<?php 


class DataBase{
    private $host = 'localhost';
    private $user = 'root';
    private $pass = 'password';
    private $databse = 'elearning';
    public $conn;

    public function __construct(){
        $this->conn = mysqli_connect($this->host,$this->user,$this->pass, $this->databse);
        if(!$this->conn){
            echo "Could not Connect";
        }
    }
}