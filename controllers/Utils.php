<?php

    require_once('../models/User.php');
    require_once("../models/Cart.php");
    require_once("../models/CartItem.php");
    require_once("../models/Product.php");
    require_once("../models/Order.php");
    require_once("../models/OrderItem.php");
    require_once("../models/Address.php");
    require_once("../models/Category.php");

    function registerUser($username, $password, $email, $first_name, $last_name) {

        $user = new User();
        $user->username = $username;
        $user->password = $password;
        $user->email = $email;
        $user->first_name = $first_name;
        $user->last_name = $last_name;

        return $user->create();
    }

    function loginUser($email, $password) {
        
        $user = new User();
        $user->email = $email;
        $user->password = $password;

        return $user->login();
    }

    function getCartIdByUserId() {
        $cart = new Cart();
        $cart->user_id = $_SESSION['userId'];
        
        return $cart->getCartIdByUserId();
    }

    function placeOrder() {
        $order = new Order();
        $order->user_id = $_SESSION['userId'];

        $order->placeOrder();
    }

    function populateCart() {
        
        $cart = new Cart();
        $cart->cartItemObj->cart_id = getCartIdByUserId();
        
        $cartItems = $cart->cartItemObj->getCartItemsById();
        $cartArr = [];
        foreach ($cartItems as $cartItem) {
            $cartArr[$cartItem['product_id']] = $cartItem['quantity'];
        }

        $_SESSION['cart'] = $cartArr;
    }

    function getCartItems($user_id) {
        $cartItem = new CartItem();
        $query = "SELECT * FROM CartItems WHERE user_id = ?";
        $stmt = $cartItem->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
    
        return $items;
    }

    function getAllProducts() {
        $product = new Product();
        $products = $product->read();
    
        if($products){
            return $products;
        }else {
            return [];
        }
    }

    function getProductById($product_id) {
        $product = new Product();
        $product->product_id = $product_id;

        return $product->getProductById();
    }

    function getProducts($productIds) {
        $product = new Product();
        $product->productIds = $productIds;

        return $product->getProducts();
    }

    function updateCart($cartItems) {
        $cart = new Cart();
        $cart->cartItems = $cartItems;

        $cart->updateCart();
    }

    function set_Cookie($name, $value, $expiry = 86400, $path = "/", $domain = "", $secure = false, $httponly = false) {
        setcookie($name, $value, time() + $expiry, $path, $domain, $secure, $httponly);
    }

    function get_Cookie($name) {
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
    }

    function getOrdersByUserId() {
        $order = new Order();
        $order->user_id = $_SESSION['userId'];

        return $order->getOrdersByUserId();
    }
    function getOrderById($order_id) {
        $order = new Order();
        $order->order_id = $order_id;

        return $order->getOrderById();
    }

    function getOrderItemsByOrderId($orderItem_id) {
        $orderItem = new OrderItem();
        $orderItem->order_id = $orderItem_id;

        return $orderItem->getOrderItemsByOrderId();
    }

    function getAddressByUserId() {
        $address = new Address();
        $address->user_id = $_SESSION['userId'];

        return $address->getAddressByUserId();
    }

    function getUserByUserId($user_id) {
        $user = new User();
        $user->user_id = $user_id;

        return $user->getUserById();
    }

?>