<?php
require_once("../controllers/dbconnection.php");
class Order {
    private $conn;
    private $table_name = "Orders";

    public $order_id;
    public $user_id;
    public $status;
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
        $query = "INSERT INTO " . $this->table_name . " (user_id, status) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("is", $this->user_id, $this->status);

        return $stmt->execute();
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->user_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        return $orders;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET status = ? WHERE order_id = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("si", $this->status, $this->order_id);

        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE order_id = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("i", $this->order_id);

        return $stmt->execute();
    }
}

?>