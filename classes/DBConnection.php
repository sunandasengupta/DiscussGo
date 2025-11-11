<?php
if(!defined('DB_SERVER')){
    require_once("../initialize.php");
}
class DBConnection{

    private $host = DB_SERVER;
    private $username = DB_USERNAME;
    private $password = DB_PASSWORD;
    private $database = DB_NAME;
    private $port = DB_PORT;
    
    public $conn;
    
    public function __construct(){

        if (!isset($this->conn)) {
            
            // Pass the port when creating the mysqli connection (supports proxy ports)
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database, $this->port);

            if ($this->conn->connect_error) {
                echo 'Cannot connect to database server: ' . $this->conn->connect_error;
                exit;
            }
        }    
        
    }
    public function __destruct(){
        $this->conn->close();
    }
}
?>