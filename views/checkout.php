<?php
session_start();

// Mock data for products. Replace this with your database query.
$products = [
    1 => [
        'name' => 'Apple',
        'price' => 1.50,
        'image' => 'images/apple.jpg'
    ],
    2 => [
        'name' => 'Banana',
        'price' => 1.00,
        'image' => 'images/banana.jpg'
    ],
    3 =>  [
        'name' => 'Orange',
        'price' => 1.20,
        'image' => 'images/orange.jpg'
    ],
    4 =>  [
        'name' => 'Grapes',
        'price' => 1.20,
        'image' => 'images/grapes.jpg'
    ],
    5 =>  [
        'name' => 'Carrot',
        'price' => 0.80,
        'image' => 'images/carrot.jpg'
    ],
    6 =>  [
        'name' => 'Lettuce',
        'price' => 0.90,
        'image' => 'images/lettuce.jpg'
    ],
    7 =>  [
        'name' => 'Tomato',
        'price' => 0.70,
        'image' => 'images/tomato.jpg'
    ],
    8 =>  [
        'name' => 'Cucumber',
        'price' => 1.10,
        'image' => 'images/cucumber.jpg'
    ],
    9 =>  [
        'name' => 'Milk',
        'price' => 2.00,
        'image' => 'images/milk.jpg'
    ],
    10 =>  [
        'name' => 'Cheese',
        'price' => 3.50,
        'image' =>  'images/cheese.jpg'
    ],
    11 =>  [
        'name' => 'Yogurt',
        'price' => 1.50,
        'image' => 'images/yogurt.jpg'
    ],
    12 =>  [
        'name' => 'Butter',
        'price' => 2.50,
        'image' => 'images/butter.jpg'
    ],
   
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Save order details here or process payment
    $shipping_address = $_POST['shipping_address'] ?? '';
    $payment_method = $_POST['payment_method'] ?? '';
    
    // Show success message and redirect
    $_SESSION['success_message'] = 'Thank you for your order! Your order has been placed successfully.';
    unset($_SESSION['cart']); // Clear the cart
    
    header('Location: checkout.php'); 
    exit;
}

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container mt-5">


    <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success text-center">
                <?php echo htmlspecialchars($_SESSION['success_message']); ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>


        <h1 class="text-center">Checkout</h1>

        
        <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
            <form method="post">
                <div class="row">
                    <div class="col-md-6">
                        <h3>Shipping Information</h3>
                        <div class="form-group">
                            <label for="shipping_address">Shipping Address</label>
                            <textarea id="shipping_address" name="shipping_address" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="payment_method">Payment Method</label>
                            <select id="payment_method" name="payment_method" class="form-control" required>
                                <option value="">Select a payment method</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="paypal">PayPal</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h3>Your Order</h3>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($_SESSION['cart'] as $product_id => $quantity): ?>
                                    <?php
                                    // Ensure product exists in the array
                                    if (!isset($products[$product_id])) {
                                        continue;
                                    }

                                    $product = $products[$product_id];
                                    $subtotal = $product['price'] * $quantity;
                                    $total += $subtotal;
                                    ?>
                                    <tr>
                                        <td><img src="<?php echo htmlspecialchars($product['image']); ?>" class="img-fluid" style="height: 100px;"></td>
                                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                                        <td>$<?php echo number_format($product['price'], 2); ?></td>
                                        <td><?php echo htmlspecialchars($quantity); ?></td>
                                        <td>$<?php echo number_format($subtotal, 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <h4>Total: $<?php echo number_format($total, 2); ?></h4>
                    </div>
                </div>
                <div class="product">
                <button type="submit" class="btn btn-success btn-lg">Place Order</button>
                                </div>
            </form>
        <?php else: ?>
            <p class="text-center lead">Your cart is empty.</p>
            <div class="product">
            <a href="home.php" class="btn btn-primary btn-lg custom-button1">Return to Home</a>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php include 'footer.php'; ?>
