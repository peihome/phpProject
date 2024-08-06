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

// Set font for the title
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Invoice', 0, 1, 'C');


// Add logo
$pdf->Image('../images/logo.jpg', 10, 10, 30); 

// Shop N Go address
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Shop N Go', 0, 1, 'R');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 10, '123 University Avenue', 0, 1, 'R');
$pdf->Cell(0, 10, 'Waterloo, ON N2J 4V3', 0, 1, 'R');
$pdf->Cell(0, 10, 'Phone: (519) 888-4567', 0, 1, 'R');
$pdf->Cell(0, 10, 'Email: info@shopandgo.com', 0, 1, 'R');

// Line break
$pdf->Ln(10);

// Invoice details
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Invoice Number: INV-' . str_pad($order['order_id'], 8, '0', STR_PAD_LEFT), 0, 1);
$pdf->Cell(0, 10, 'Invoice Date: ' . date('Y-m-d'), 0, 1);

$pdf->SetTitle('INV-' . str_pad($order['order_id'], 8, '0', STR_PAD_LEFT));

// Line break
$pdf->Ln(10);

// Customer details
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Customer Details', 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Customer Name: ' . $user['first_name'] . ' ' . $user['last_name'], 0, 1);
$pdf->Cell(0, 10, 'Email: ' . $user['email'], 0, 1);
$pdf->Cell(0, 10, 'Address: ' . $address['street'] . ', ' . $address['city'] . ', ' . $address['state'] . ' ' . $address['postal_code'] . ', ' . $address['country'], 0, 1);

// Line break
$pdf->Ln(10);

// Order details header
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Order ID: ' . $order['order_id']);
$pdf->Ln(10);
$pdf->Cell(40, 10, 'Order Date: ' . $order['created_at']);
$pdf->Ln(10);

// Table header
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Product ID', 1);
$pdf->Cell(60, 10, 'Product Name', 1);
$pdf->Cell(30, 10, 'Quantity', 1);
$pdf->Cell(30, 10, 'Price', 1);
$pdf->Ln(10);

// Table body
$pdf->SetFont('Arial', '', 12);
$total = 0;
foreach ($order_items as $key => $item) {
    $pdf->Cell(40, 10, $item['product_id'], 1);
    $pdf->Cell(60, 10, $products[$item['product_id']]['name'], 1);
    $pdf->Cell(30, 10, $item['quantity'], 1);
    $pdf->Cell(30, 10, '$' . number_format($item['price'], 2), 1);
    $pdf->Ln(10);
    $total += $item['price'];
}

// Add total
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(130, 10, 'Total', 1);
$pdf->Cell(30, 10, '$' . number_format($total, 2), 1);

// Line break after the table
$pdf->Ln(20);

// Add a new page for the thank you message
$pdf->AddPage();

// Thank you message
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Thank You for Shopping at Shop N Go!', 0, 1, 'C');

// Line break
$pdf->Ln(10);

// Thank you message details
$pdf->SetFont('Arial', '', 12);
$message = "Dear " . htmlspecialchars($user['first_name']) . ",\n\nThank you for choosing Shop N Go for your shopping needs. We appreciate your trust in us and hope you had a great shopping experience.\n\nWe look forward to serving you again soon!\n\nBest regards,\nShop N Go Team";
$pdf->MultiCell(0, 10, $message);

// Output the PDF
$pdf->Output('I', 'Invoice_' . $order['order_id'] . '.pdf');
?>
