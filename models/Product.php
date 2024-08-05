<?php
require_once("../controllers/dbconnection.php");

class Product {
    private $conn;
    private $table_name = "Products";
    private $category_table = "";

    public $product_id;
    public $productIds;
    public $name;
    public $description;
    public $price;
    public $stock_quantity;
    public $category_id;
    public $image_url;

    public function __construct() {
        $dbController = new DBController();
        $this->conn = $dbController->connectDB();

        $category = new Category();
        $this->category_table = $category->getTableName();
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
        $query = "SELECT p.product_id, p.name, p.description, p.price, p.stock_quantity, c.name as category, p.image_url  FROM " . $this->table_name . " p, " . $this->category_table . " c WHERE p.category_id = c.category_id";
        $result = $this->conn->query($query);

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        return $products;
    }

    public function getProductById() {
        try {
            $query = "SELECT p.product_id, p.name, p.description, p.price, p.stock_quantity, c.name as category, p.image_url
                    FROM " . $this->table_name . " p
                    JOIN " . $this->category_table . " c ON p.category_id = c.category_id
                    WHERE p.product_id = ?";
            $stmt = $this->conn->prepare($query);
        
            $stmt->bind_param("i", $this->product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();
            
            return $product;
        }catch(Exception $e) {
            return [];
        }
    }

    public function getProducts() : array {
        try {
            if (empty($this->productIds)) {
                return [];
            }
    
            $placeholders = implode(',', array_fill(0, count($this->productIds), '?'));
    
            $query = "SELECT p.product_id, p.name, p.description, p.price, p.stock_quantity, c.name as category, p.image_url
                      FROM " . $this->table_name . " p
                      JOIN " . $this->category_table . " c ON p.category_id = c.category_id
                      WHERE p.product_id IN ($placeholders)";
            
            $stmt = $this->conn->prepare($query);
    
            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . $this->conn->error);
            }

            $types = str_repeat('i', count($this->productIds));
            $stmt->bind_param($types, ...$this->productIds);
    
            $stmt->execute();
            $result = $stmt->get_result();
    
            $products = [];
            while ($row = $result->fetch_assoc()) {
                $products[$row['product_id']] = $row;
            }

            return $products;
        } catch (Exception $e) {
            return [];
        }
    }
}

?>