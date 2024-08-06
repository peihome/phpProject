<?php

require_once("../controllers/dbconnection.php");

class CartItem {
    private $conn;
    public $table_name = "CartItems";
    private $productObj;
    public $cart_item_id;
    public $cart_id;
    public $product_id;
    public $price;
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

    public function getCartItemsById() {
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

    public function getCartItemByIdAndProductId() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE cart_id = ? AND product_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $this->cart_id, $this->product_id);
        $stmt->execute();
        
        return $stmt->get_result();
    }

    public function updateCartItemByIdAndProductId() {
        $query = "UPDATE " . $this->table_name . " SET quantity = ? WHERE cart_id = ? AND product_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iii", $this->quantity, $this->cart_id, $this->product_id);
        $stmt->execute();
    }

    public function insertCartItemByIdAndProductId() {
        $query = "INSERT INTO " . $this->table_name . " (cart_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iiid", $this->cart_id, $this->product_id, $this->quantity, $this->price);
        $stmt->execute();
    }

    public function removeCartItemsById() {
        $query = "DELETE FROM " . $this->table_name . " WHERE cart_id = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("i", $this->cart_id);

        $stmt->execute();
    }
}

?>