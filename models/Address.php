<?php
require_once("../controllers/dbconnection.php");
class Address {
    private $conn;
    private $table_name = "Addresses";

    public $address_id;
    public $user_id;
    public $street;
    public $city;
    public $state;
    public $postal_code;
    public $country;

    public function __construct() {
        $dbController = new DBController();
        $this->conn = $dbController->connectDB();
    }

    public function __destruct() {
        $this->conn->close();
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (user_id, street, city, state, postal_code, country) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("isssss", $this->user_id, $this->street, $this->city, $this->state, $this->postal_code, $this->country);

        return $stmt->execute();
    }

    public function getAddressByUserId() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->user_id);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET street = ?, city = ?, state = ?, postal_code = ?, country = ? WHERE address_id = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("sssssi", $this->street, $this->city, $this->state, $this->postal_code, $this->country, $this->address_id);

        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE address_id = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("i", $this->address_id);

        return $stmt->execute();
    }
}

?>