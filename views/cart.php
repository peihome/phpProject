<?php
session_start();
$_SESSION['page'] = 'Cart';
include 'header.php';

if (!isset($_SESSION['email'])) {
    $_SESSION['message'] = "Please login!";
    $_SESSION['redirect_url'] = "cart.php";
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isdelete = isset($_POST['delete']);
    if ($isdelete) {
        $deleteProductId = $_POST['delete'];
        unset($_SESSION['cart'][$deleteProductId]);
    }else {
        foreach ($_POST['quantity'] as $product_id => $quantity) {
            if (is_numeric($quantity) && $quantity > 0) {
                $_SESSION['cart'][$product_id] = (float)$quantity;
            } else {
                unset($_SESSION['cart'][$product_id]);
            }
        }
    }
    
    updateCart($_SESSION['cart'], $isdelete);
    
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
                                <td class="quantity">
                                    <input type="number" name="quantity[<?php echo htmlspecialchars($product_id); ?>]" value="<?php echo htmlspecialchars($quantity); ?>" min="1" max="20" step="1" class="form-control" style="width: 100px;">
                                    <button type="submit" class="btn btn-outline-danger" name="delete" value="<?php echo htmlspecialchars($product_id); ?>">Remove
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                        </svg>
                                    </button>
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