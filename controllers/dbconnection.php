<?php
//Below code contains the DB connection model where connection to database is checked and dbinit.php is called if database doesn't exists

class DBController {
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $database = "grocerystore";
    
    private $conn;
    
    function __construct() {
        try{
            $this->conn = $this->connectDB();

            if(!empty($this->conn)) {
                $this->selectDB();
            }else {
                $this->reInitDB();    
            }

        }catch(Exception $e){
            $this->reInitDB();
        }
    }

    function reInitDB() {
        define('DB_HOST', $this->host);
        define('DB_USER', $this->user);
        define('DB_PASSWORD', $this->password);
        define('DB_NAME', $this->database);

        require_once("../controllers/dbinit.php");

        $this->retryConnection();
    }

    function retryConnection() {
        $this->conn = $this->connectDB();
        $this->selectDB();
    }
    
    function __destruct() {
        // Close the database connection
        $this->conn->close();
    }
    
    function connectDB() {
        return new mysqli($this->host,$this->user,$this->password,$this->database);
    }
    
    function selectDB() {
        mysqli_select_db($this->conn, $this->database);
    }
    
    function runQuery($query) {
        $result = mysqli_query($this->conn, $query);
        while($row=mysqli_fetch_assoc($result)) {
            $resultset[] = $row;
        }
        return $resultset;
    }

    public function prepare($query) {
        return $this->conn->prepare($query);
    }
}