<?php
session_start();

require_once('../controllers/Utils.php');
require_once('../lib/fpdf.php');

// Fetch order details
$order = getOrderById(htmlspecialchars(trim($_GET['order_id'] ?? '')));

$user = getUserByUserId($order['user_id']);

$address = getAddressByUserId();

$order_items = getOrderItemsByOrderId($order['order_id']);

$productIds = [];
foreach ($order_items as $key => $item) {
    $productIds[] = $item['product_id'];
}

$products = getProducts($productIds);

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
foreach ($order_items as $key => $item) {
    $pdf->Cell(40, 10, $item['product_id'], 1);
    $pdf->Cell(60, 10, $products[$item['product_id']]['name'], 1);
    $pdf->Cell(30, 10, $item['quantity'], 1);
    $pdf->Cell(30, 10, '$' . $item['price'], 1);
    $pdf->Ln(10);
}

// Output the PDF
$pdf->Output();
?>