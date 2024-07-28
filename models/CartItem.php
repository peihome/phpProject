<?php

require_once("../controllers/dbconnection.php");

class CartItem {
    private $conn;
    private $table_name = "CartItems";

    public $cart_item_id;
    public $cart_id;
    public $product_id;
    public $quantity;
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
        $query = "INSERT INTO " . $this->table_name . " (cart_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("iii", $this->cart_id, $this->product_id, $this->quantity);

        return $stmt->execute();
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE cart_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->cart_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $cartItems = [];
        while ($row = $result->fetch_assoc()) {
            $cartItems[] = $row;
        }
        return $cartItems;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET quantity = ? WHERE cart_item_id = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("ii", $this->quantity, $this->cart_item_id);

        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE cart_item_id = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("i", $this->cart_item_id);

        return $stmt->execute();
    }
}

?>