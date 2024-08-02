<?php
require_once('../lib/fpdf.php');

// Database connection
$conn = new mysqli('localhost', 'root', '', 'ShopNGo');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch order details
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 1; // Example order ID, you can get this dynamically
$order_sql = "SELECT * FROM Orders WHERE order_id = $order_id";
$order_result = $conn->query($order_sql);
$order = $order_result->fetch_assoc();

$user_sql = "SELECT * FROM Users WHERE user_id = " . $order['user_id'];
$user_result = $conn->query($user_sql);
$user = $user_result->fetch_assoc();

$address_sql = "SELECT * FROM Addresses WHERE user_id = " . $order['user_id'];
$address_result = $conn->query($address_sql);
$address = $address_result->fetch_assoc();

$order_items_sql = "SELECT * FROM orderitems WHERE order_id = $order_id";
$order_items_result = $conn->query($order_items_sql);

// Create instance of FPDF class
$pdf = new FPDF();

// Add a page
$pdf->AddPage();

// Add logo
$pdf->Image('../images/logo.jpg', 10, 10, 30); // Adjust the path and size as needed

// Set font
$pdf->SetFont('Arial', 'B', 16);

// Add a cell for the title
$pdf->Cell(0, 10, 'Invoice', 0, 1, 'C');

// Line break
$pdf->Ln(20);

// Customer details
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(40, 10, 'Customer Name: ' . $user['first_name'] . ' ' . $user['last_name']);
$pdf->Ln(10);
$pdf->Cell(40, 10, 'Email: ' . $user['email']);
$pdf->Ln(10);
$pdf->Cell(40, 10, 'Address: ' . $address['street'] . ', ' . $address['city'] . ', ' . $address['state'] . ', ' . $address['postal_code'] . ', ' . $address['country']);
$pdf->Ln(10);

// Order details header
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Order ID: ' . $order['order_id']);
$pdf->Ln(10);
$pdf->Cell(40, 10, 'Order Date: ' . $order['created_at']);
$pdf->Ln(10);
$pdf->Cell(40, 10, 'Total Amount: $' . $order['total_price']);
$pdf->Ln(20);

// Table header
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Product ID', 1);
$pdf->Cell(60, 10, 'Product Name', 1);
$pdf->Cell(30, 10, 'Quantity', 1);
$pdf->Cell(30, 10, 'Price', 1);
$pdf->Ln(10);

// Table body
$pdf->SetFont('Arial', '', 12);
while ($row = $order_items_result->fetch_assoc()) {
    $product_sql = "SELECT name FROM Products WHERE product_id = " . $row['product_id'];
    $product_result = $conn->query($product_sql);
    $product = $product_result->fetch_assoc();
    
    $pdf->Cell(40, 10, $row['product_id'], 1);
    $pdf->Cell(60, 10, $product['name'], 1);
    $pdf->Cell(30, 10, $row['quantity'], 1);
    $pdf->Cell(30, 10, '$' . $row['price'], 1);
    $pdf->Ln(10);
}

// Output the PDF
$pdf->Output();
?>
