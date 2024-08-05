<?php
include 'header.php';

if (!isset($_SESSION['email'])) {
    $_SESSION['message'] = "Please login!";
    header('Location: login.php');
}

populateCart();

$cart = $_SESSION['cart'];
$productIds = [];
foreach ($cart as $key => $item) {
    $productIds[] = $key;
}

$products = getProducts($productIds);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Save order details here or process payment
    $shipping_address = $_POST['shipping_address'] ?? '';
    $payment_method = $_POST['payment_method'] ?? '';

    placeOrder();
    // Show success message and redirect
    $_SESSION['success_message'] = 'Thank you for your order! Your order has been placed successfully.';
    unset($_SESSION['cart']);
    unset($_SESSION['cartId']);

    header('Location: checkout.php');
    exit;
}

$total = 0;
?>

<body>
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
                            <textarea id="shipping_address" name="shipping_address" class="form-control" rows="4"
                                required></textarea>
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
                                        <td><img src="<?php echo htmlspecialchars($product['image_url']); ?>" class="img-fluid"
                                                style="height: 100px;"></td>
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
                <div class="product pT2">
                    <button type="submit" class="btn btn-success btn-lg">Place Order</button>
                </div>
            </form>
        <?php else: ?>
            <p class="text-center lead">Your cart is empty.</p>
            <div class="product pT2">
                <a href="home.php" class="btn btn-primary btn-lg custom-button1">Return to Home</a>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>

<?php include 'footer.php'; ?>