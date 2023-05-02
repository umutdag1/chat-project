<?php

class Database {
    private $dbhost = "localhost";
    private $dbuser = "root";
    private $dbpass = "";
    private $dbname = "chat_project";

    public function connect() {
        $mysql_conn = "mysql:host=$this->dbhost;dbname=$this->dbname;charset=utf8";
        $conn = new PDO($mysql_conn, $this->dbuser, $this->dbpass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        return $conn;
    }
}