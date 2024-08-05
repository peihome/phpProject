<?php
require_once("../controllers/dbconnection.php");

class Order {
    private $conn;
    private $table_name = "Orders";

    private $cartObj;
    private $orderItemObj;
    private $addressObj;
    private $userObj;

    public $shipping_address;
    public $order_id;
    public $user_id;
    public $status;
    public $created_at;
    public $updated_at;

    public function __construct() {
        $dbController = new DBController();
        $this->conn = $dbController->connectDB();

        $this->cartObj = new Cart();
        $this->orderItemObj = new OrderItem();
        $this->addressObj = new Address();
        $this->userObj = new User();
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

    public function placeOrder() {

        //Update Address
        $this->addressObj->user_id = $this->user_id;
        $address = $this->addressObj->getAddressByUserId();
        
        $this->addressObj->street = $this->shipping_address['street'];
        $this->addressObj->city = $this->shipping_address['city'];
        $this->addressObj->state = $this->shipping_address['state'];
        $this->addressObj->postal_code = $this->shipping_address['postal_code'];
        $this->addressObj->country = $this->shipping_address['country'];

        if(!isset($address)){
            $this->addressObj->create();
        }else {
            $this->addressObj->address_id = $address['address_id'];
            $this->addressObj->updateAddressById();
        }

        
        //Update Phone Number
        $this->userObj->user_id = $this->user_id;
        $this->userObj->phone = $this->shipping_address['phone'];
        $this->userObj->updatePhoneNumberById();


        // Fetch active cart for the user
        $query = "SELECT * FROM " . $this->cartObj->table_name . " WHERE user_id = ? AND is_active = TRUE";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->user_id);
        $stmt->execute();
        $cart_result = $stmt->get_result();
        
        if ($cart_result->num_rows == 0) {
            return "No active cart found for the user.";
        }

        $cart = $cart_result->fetch_assoc();
        $cart_id = $cart['cart_id'];

        // Fetch cart items
        $query = "SELECT * FROM " . $this->cartObj->cartItemObj->table_name . " WHERE cart_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $cart_id);
        $stmt->execute();
        $cart_items_result = $stmt->get_result();

        if ($cart_items_result->num_rows == 0) {
            return "No items in the cart to place an order.";
        }

        $total_price = 0;
        $cart_items = [];

        while ($item = $cart_items_result->fetch_assoc()) {
            $total_price += $item['price'];
            $cart_items[] = $item;
        }

        // Insert new order
        $query = "INSERT INTO " . $this->table_name . " (user_id, total_price, status, created_at, updated_at) VALUES (?, ?, 'Confirmed', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("id", $this->user_id, $total_price);
        
        if (!$stmt->execute()) {
            return "Error placing order: " . $stmt->error;
        }

        $order_id = $stmt->insert_id;

        // Insert cart items into order items
        $query = "INSERT INTO " . $this->orderItemObj->table_name . " (order_id, product_id, quantity, price, created_at) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)";
        $stmt = $this->conn->prepare($query);

        foreach ($cart_items as $item) {
            $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
            if (!$stmt->execute()) {
                return "Error inserting order item: " . $stmt->error;
            }
        }

        // Update cart to inactive
        $query = "UPDATE " . $this->cartObj->table_name . " SET is_active = FALSE WHERE cart_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $cart_id);
        if (!$stmt->execute()) {
            return "Error updating cart: " . $stmt->error;
        }
        
        return true;
    }
    function getOrdersByUserId() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = ? ORDER BY created_at DESC";
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

    function getOrderById() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE order_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->order_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }
}

?>