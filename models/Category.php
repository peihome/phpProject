<?php
require_once("../controllers/dbconnection.php");
class Category {
    private $conn;
    private $table_name = "Categories";

    public $category_id;
    public $name;
    public $description;

    public function __construct() {
        $dbController = new DBController();
        $this->conn = $dbController->connectDB();
    }

    public function __destruct() {
        $this->conn->close();
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (name, description) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("ss", $this->name, $this->description);

        return $stmt->execute();
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $result = $this->conn->query($query);

        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
        return $categories;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET name = ?, description = ? WHERE category_id = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("ssi", $this->name, $this->description, $this->category_id);

        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE category_id = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("i", $this->category_id);

        return $stmt->execute();
    }
}

?>