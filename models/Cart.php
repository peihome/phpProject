<?php
require_once("../controllers/dbconnection.php");

class Cart {
    private $conn;
    public $table_name = "Carts";

    public $cartItemObj;

    public $cartItems;
    public $cart_id;
    public $user_id;
    public $created_at;
    public $updated_at;
    public $isdelete;

    public function __construct() {
        $dbController = new DBController();
        $this->conn = $dbController->connectDB();

        $this->cartItemObj = new CartItem();
    }

    public function __destruct() {
        $this->conn->close();
    }

    public function createAndGetCartIdByUserId() {
        $query = "INSERT INTO " . $this->table_name . " (user_id, is_active) VALUES (?, TRUE)";
        $stmt = $this->conn->prepare($query);
    
        $stmt->bind_param("i", $this->user_id);
    
        if ($stmt->execute()) {
            $cart_id = $this->conn->insert_id;
            $stmt->close();
            return $cart_id;
        } else {
            $stmt->close();
            return false;
        }
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

    public function getCartIdByUserId() {
        $query = "SELECT cart_id FROM " . $this->table_name . " WHERE user_id = ? AND is_active = true";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->user_id);
        $stmt->execute();
        
        $stmt->bind_result($cart_id);
        $stmt->fetch();
        $stmt->close();
        
        return $cart_id ? $cart_id : null;
    }

    public function updateCart() {
        $this->user_id = $_SESSION['userId'];
        if(!isset($this->user_id)){
            return;
        }

        $this->cart_id = $_SESSION['cartId'];
        if(!isset($this->cart_id)){
            $this->cart_id = $this->getCartIdByUserId();
        }
        
        if(!isset($this->cart_id)){
            $this->cart_id = $this->createAndGetCartIdByUserId();
            $_SESSION['cartId'] = $this->cart_id;
        }

        if($this->isdelete){
            $this->cartItemObj->cart_id = $this->cart_id;
            $this->cartItemObj->removeCartItemsById();
        }

        $cart = $_SESSION['cart'];
        $productIds = [];
        foreach ($cart as $key => $item) {
            $productIds[] = $key;
        }
        $products = getProducts($productIds);

        $this->cartItemObj->cart_id = $this->cart_id;
        foreach ($this->cartItems as $productId => $quantity) {
            
            $this->cartItemObj->product_id = $productId;
            $this->cartItemObj->quantity = $quantity;
            $this->cartItemObj->price = $quantity * $products[$productId]['price'];

            $result = $this->cartItemObj->getCartItemByIdAndProductId();

            if ($result->num_rows > 0) {
                $this->cartItemObj->updateCartItemByIdAndProductId();
            } else {
                $this->cartItemObj->insertCartItemByIdAndProductId();
            }
        }
    }
}

?>