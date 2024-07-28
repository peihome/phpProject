<?php
require_once("../controllers/dbconnection.php");

class User {
    private $conn;
    private $table_name = "Users";

    public $user_id;
    public $username;
    public $password_hash;
    public $email;
    public $first_name;
    public $last_name;
    public $created_at;
    public $updated_at;

    public function __construct() {
        $dbController = new DBController();
        $this->conn = $dbController->connectDB();
    }

    public function __destruct() {
        $this->conn->close();
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (username, password_hash, email, first_name, last_name) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("sssss", $this->username, $this->password_hash, $this->email, $this->first_name, $this->last_name);

        return $stmt->execute();
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $result = $this->conn->query($query);

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET username = ?, password_hash = ?, email = ?, first_name = ?, last_name = ? WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("sssssi", $this->username, $this->password_hash, $this->email, $this->first_name, $this->last_name, $this->user_id);

        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("i", $this->user_id);

        return $stmt->execute();
    }
}
?>