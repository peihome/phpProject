<?php
include 'header.php';

if (!isset($_SESSION['email'])) {
    $_SESSION['message'] = "Please login!";
    $_SESSION['redirect_url'] = "cart.php";
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        if (is_numeric($quantity) && $quantity > 0) {
            $_SESSION['cart'][$product_id] = (float)$quantity;
        } else {
            unset($_SESSION['cart'][$product_id]);
        }
    }
    updateCart($_SESSION['cart']);
    
    header('Location: cart.php');
    exit;
}

if(!isset($_SESSION['cartId'])){
    $_SESSION['cartId'] = getCartIdByUserId();
}

populateCart();
$cart = $_SESSION['cart'];

$productIds = [];
foreach ($cart as $key => $item) {
    $productIds[] = $key;
}

$products = getProducts($productIds);

// Initialize total
$total = 0;
?>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Your Shopping Cart</h1>
        <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
            <form method="post">
                <table class="table table-bordered">
                    <thead class="head-back">
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
                                <td><img src="<?php echo htmlspecialchars($product['image_url']); ?>" class="img-fluid" style="height: 100px;"></td>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td>$<?php echo number_format($product['price'], 2); ?></td>
                                <td>
                                    <input type="number" name="quantity[<?php echo htmlspecialchars($product_id); ?>]" value="<?php echo htmlspecialchars($quantity); ?>" min="1" max="20" step="1" class="form-control" style="width: 100px;">
                                </td>
                                <td>$<?php echo number_format($subtotal, 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="text-right">
                    <h3>Total: $<?php echo number_format($total, 2); ?></h3>
                    <button type="submit" class="btn btn-primary btn-lg custom-button1">Update Cart</button>
                </div>
            </form>
            <div class="product">
            <a href="checkout.php" class="btn btn-success btn-lg mt-4 custom-button1">Proceed to Checkout</a>
        <?php else: ?>
            <div class="text-center">
                <p>Your cart is empty.</p>
                <img src="../images/empty_cart.png" alt="Empty Cart" class="img-fluid" style="max-width: 300px;">
            </div>
        <?php endif; ?>
        </div>
    </div>
</body>

<?php include 'footer.php'; ?>