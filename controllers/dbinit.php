<?php

// Database configuration
$host = defined('DB_HOST') ? DB_HOST : "localhost";
$user = defined('DB_USER') ? DB_USER : "root";
$password = defined('DB_PASSWORD') ? DB_PASSWORD : "";
$database = defined('DB_NAME') ? DB_NAME : "grocerystore";

// Create connection
$conn = new mysqli($host, $user, $password);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $database";
if ($conn->query($sql) === TRUE) {
    $conn->select_db($database);
} else {
    die("Error creating database: " . $conn->error . "\n");
}

// Create tables
$sql = "
CREATE TABLE IF NOT EXISTS Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    phone VARCHAR(15),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

ALTER TABLE Users AUTO_INCREMENT = 1000;

CREATE TABLE IF NOT EXISTS Categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT
);

ALTER TABLE Categories AUTO_INCREMENT = 100;

CREATE TABLE IF NOT EXISTS Products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock_quantity INT NOT NULL,
    category_id INT,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES Categories(category_id)
);

ALTER TABLE Products AUTO_INCREMENT = 100;

CREATE TABLE IF NOT EXISTS Carts (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    is_active BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

ALTER TABLE Carts AUTO_INCREMENT = 200;

CREATE TABLE IF NOT EXISTS CartItems (
    cart_item_id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (cart_id) REFERENCES Carts(cart_id),
    FOREIGN KEY (product_id) REFERENCES Products(product_id)
);

ALTER TABLE CartItems AUTO_INCREMENT = 200;

CREATE TABLE IF NOT EXISTS Orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    status VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

ALTER TABLE Orders AUTO_INCREMENT = 1000;

CREATE TABLE IF NOT EXISTS OrderItems (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES Orders(order_id),
    FOREIGN KEY (product_id) REFERENCES Products(product_id)
);

ALTER TABLE OrderItems AUTO_INCREMENT = 1000;

CREATE TABLE IF NOT EXISTS Addresses (
    address_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    street VARCHAR(255) NOT NULL,
    city VARCHAR(255) NOT NULL,
    state VARCHAR(255) NOT NULL,
    postal_code VARCHAR(20) NOT NULL,
    country VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

ALTER TABLE Addresses AUTO_INCREMENT = 2000;
";

// Execute the SQL to create tables
if ($conn->multi_query($sql)) {
    do {
        // Store the result to clear the results of the multi-query
        if ($result = $conn->store_result()) {
            $result->free();
        }
    } while ($conn->next_result());
    echo "Tables created successfully.\n";
} else {
    die("Error creating tables: " . $conn->error . "\n");
}

// Populate tables with random data
$populateSql = "
INSERT INTO Categories (name, description) VALUES
('Fruits', 'Fresh and juicy fruits'),
('Vegetables', 'Fresh vegetables'),
('Dairy', 'Milk, cheese, and other dairy products');

INSERT INTO Products (name, description, price, stock_quantity, category_id, image_url) VALUES
('Apple', 'Fresh red apples', 0.99, 100, 100, '../images/apple.jpg'),
('Banana', 'Fresh yellow bananas', 0.59, 150, 100, '../images/banana.jpg'),
('Orange', 'Citrusy and refreshing oranges', 1.20, 200, 100, '../images/orange.jpg'),
('Grapes', 'Fresh and sweet grapes', 1.20, 50, 100, '../images/grapes.jpg'),
('Carrot', 'Crunchy and healthy carrots', 0.80, 150, 101, '../images/carrot.jpg'),
('Lettuce', 'Fresh and crispy lettuce', 0.90, 100, 101, '../images/lettuce.jpg'),
('Tomato', 'Ripe and juicy tomatoes', 0.70, 80, 101, '../images/tomato.jpg'),
('Cucumber', 'Cool and refreshing cucumbers', 1.10, 90, 101, '../images/cucumber.jpg'),
('Milk', 'Fresh dairy milk', 2.00, 110, 102, '../images/milk.jpg'),
('Cheese', 'Delicious cheese', 3.50, 120, 102, '../images/cheese.jpg'),
('Yogurt', 'Creamy and healthy yogurt', 1.50, 130, 102, '../images/yogurt.jpg'),
('Butter', 'Rich and creamy butter', 2.50, 70, 102, '../images/butter.jpg');

";

// Execute the SQL to populate tables
if ($conn->multi_query($populateSql)) {
    do {
        // Store the result to clear the results of the multi-query
        if ($result = $conn->store_result()) {
            $result->free();
        }
    } while ($conn->next_result());
    echo "Tables populated with sample data.\n";
} else {
    die("Error populating tables: " . $conn->error . "\n");
}

// Close connection
$conn->close();
?>