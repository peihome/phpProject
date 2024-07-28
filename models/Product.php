<?php
require_once("../controllers/dbconnection.php");
class Product {
    private $conn;
    private $table_name = "Products";

    public $product_id;
    public $name;
    public $description;
    public $price;
    public $stock_quantity;
    public $category_id;
    public $image_url;

    public function __construct() {
        $dbController = new DBController();
        $this->conn = $dbController->connectDB();
    }

    public function __destruct() {
        $this->conn->close();
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (name, description, price, stock_quantity, category_id, image_url) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("ssdiis", $this->name, $this->description, $this->price, $this->stock_quantity, $this->category_id, $this->image_url);

        return $stmt->execute();
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $result = $this->conn->query($query);

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        return $products;
    }
}

?>