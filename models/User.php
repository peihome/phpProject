<?php
require_once("../controllers/dbconnection.php");

class User {
    private $conn;
    private $table_name = "Users";

    public $user_id;
    public $username;
    public $password;
    public $email;
    public $first_name;
    public $last_name;
    public $created_at;
    public $updated_at;

    public function __construct() {
        $dbController = new DBController();
        $this->conn = $dbController->connectDB();
    }

    public function __destruct() {
        $this->conn->close();
    }

    public function validateUser() {
        // Check if username already exists
        $usernameExists = $this->getRowByUserName($this->username);
        if ($usernameExists) {
            return "Username already exists.";
        }

        // Check if email already exists
        $emailExists = $this->getRowByEmail();
        if ($emailExists) {
            return "Email already exists.";
        }

        // Validate password
        if ($this->password) {
            if (strlen($this->password) < 8 || strlen($this->password) > 16) {
                return "Password must be between 8 and 16 characters.";
            }
            if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $this->password)) {
                return "Password must contain at least one special character.";
            }
            if (!preg_match('/[0-9]/', $this->password)) {
                return "Password must contain at least one number.";
            }
        }

        // Validate first name
        if (!preg_match('/^[a-zA-Z ]{1,50}$/', $this->first_name)) {
            return "First name must contain only English alphabets and be at most 50 characters long.";
        }

        // Validate last name
        if (!preg_match('/^[a-zA-Z ]{1,50}$/', $this->last_name)) {
            return "Last name must contain only English alphabets and be at most 50 characters long.";
        }

        return true;
    }

    public function login() {
        if(!isset($this->email)) {
            return "Email cannot be empty!";
        }

        if(!isset($this->password)) {
            return "Password cannot be empty!";
        }

        return $this->loginUserByEmailAndPassword();
    }

    public function create() {
        // Validate user inputs
        $validation_response = $this->validateUser();
        
        // Return the validation error message if validation fails
        if (!is_bool($validation_response) || $validation_response !== true) {
            return $validation_response;
        }
        
        // Set the created_at timestamp
        $this->created_at = date('Y-m-d H:i:s');
        
        // Hash the password
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        
        // Prepare the SQL query
        $query = "INSERT INTO " . $this->table_name . " (username, password_hash, email, first_name, last_name, created_at) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssss", $this->username, $this->password, $this->email, $this->first_name, $this->last_name, $this->created_at);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return "Error creating user: " . $stmt->error;
        }
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $result = $this->conn->query($query);

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }

    public function getRowByUserName($username) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function loginUserByEmailAndPassword() {
        // Query to retrieve the user_id and hashed password based on the email
        $query = "SELECT user_id, password_hash FROM " . $this->table_name . " WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        $stmt->bind_result($this->user_id, $stored_password_hash);
        $stmt->fetch();
        $stmt->close();
        
        // Check if the email exists and the password is correct
        if ($stored_password_hash) {
            if (password_verify($this->password, $stored_password_hash)) {
                return $this->user_id;
            } else {
                return "Invalid password!";
            }
        } else {
            return "Invalid email!";
        }
    }

    public function getRowByEmail() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET username = ?, password = ?, email = ?, first_name = ?, last_name = ? WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("sssssi", $this->username, $this->password, $this->email, $this->first_name, $this->last_name, $this->user_id);

        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("i", $this->user_id);

        return $stmt->execute();
    }

    public function getUserById() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("i", $this->user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }
}
?>