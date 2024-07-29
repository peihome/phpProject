<?php
require_once("../controllers/dbconnection.php");

class Cart {
    private $conn;
    private $table_name = "Carts";

    public $cart_id;
    public $user_id;
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
        $query = "INSERT INTO " . $this->table_name . " (user_id) VALUES (?)";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("i", $this->user_id);

        return $stmt->execute();
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->user_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $carts = [];
        while ($row = $result->fetch_assoc()) {
            $carts[] = $row;
        }
        return $carts;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET user_id = ? WHERE cart_id = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("ii", $this->user_id, $this->cart_id);

        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE cart_id = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("i", $this->cart_id);

        return $stmt->execute();
    }
}

?>