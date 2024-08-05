<?php
require_once("../controllers/dbconnection.php");
class OrderItem {
    private $conn;
    public $table_name = "OrderItems";

    public $order_item_id;
    public $order_id;
    public $product_id;
    public $quantity;
    public $price;

    public function __construct() {
        $dbController = new DBController();
        $this->conn = $dbController->connectDB();
    }

    public function __destruct() {
        $this->conn->close();
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("iiid", $this->order_id, $this->product_id, $this->quantity, $this->price);

        return $stmt->execute();
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE order_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->order_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $orderItems = [];
        while ($row = $result->fetch_assoc()) {
            $orderItems[] = $row;
        }
        return $orderItems;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET quantity = ?, price = ? WHERE order_item_id = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("idi", $this->quantity, $this->price, $this->order_item_id);

        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE order_item_id = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("i", $this->order_item_id);

        return $stmt->execute();
    }

    public function getOrderItemsByOrderId() {
        $query = "SELECT oi.*, p.name as product_name FROM " . $this->table_name . " oi 
                  JOIN Products p ON oi.product_id = p.product_id 
                  WHERE oi.order_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->order_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $orderItems = [];
        while ($row = $result->fetch_assoc()) {
            $orderItems[] = $row;
        }
        return $orderItems;
    }
}

?>