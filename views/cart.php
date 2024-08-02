<?php
session_start();

// Mock data for products. Replace this with your database query.
$products = [
    1 => [
        'name' => 'Apple',
        'price' => 1.50,
        'image' => '../images/apple.jpg'
    ],
    2 => [
        'name' => 'Banana',
        'price' => 1.00,
        'image' => '../images/banana.jpg'
    ],
    3 =>  [
        'name' => 'Orange',
        'price' => 1.20,
        'image' => '../images/orange.jpg'
    ],
    4 =>  [
        'name' => 'Grapes',
        'price' => 1.20,
        'image' => '../images/grapes.jpg'
    ],
    5 =>  [
        'name' => 'Carrot',
        'price' => 0.80,
        'image' => '../images/carrot.jpg'
    ],
    6 =>  [
        'name' => 'Lettuce',
        'price' => 0.90,
        'image' => '../images/lettuce.jpg'
    ],
    7 =>  [
        'name' => 'Tomato',
        'price' => 0.70,
        'image' => '../images/tomato.jpg'
    ],
    8 =>  [
        'name' => 'Cucumber',
        'price' => 1.10,
        'image' => '../images/cucumber.jpg'
    ],
    9 =>  [
        'name' => 'Milk',
        'price' => 2.00,
        'image' => '../images/milk.jpg'
    ],
    10 =>  [
        'name' => 'Cheese',
        'price' => 3.50,
        'image' =>  '../images/cheese.jpg'
    ],
    11 =>  [
        'name' => 'Yogurt',
        'price' => 1.50,
        'image' => '../images/yogurt.jpg'
    ],
    12 =>  [
        'name' => 'Butter',
        'price' => 2.50,
        'image' => '../images/butter.jpg'
    ],
    
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        // Ensure quantity is numeric and greater than 0
        if (is_numeric($quantity) && $quantity > 0) {
            $_SESSION['cart'][$product_id] = (float)$quantity;
        } else {
            unset($_SESSION['cart'][$product_id]);
        }
    }
    header('Location: cart.php');
    exit;
}

// Initialize total
$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
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
                                <td><img src="<?php echo htmlspecialchars($product['image']); ?>" class="img-fluid" style="height: 100px;"></td>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td>$<?php echo number_format($product['price'], 2); ?></td>
                                <td>
                                    <input type="number" name="quantity[<?php echo htmlspecialchars($product_id); ?>]" value="<?php echo htmlspecialchars($quantity); ?>" min="0.1" step="0.1" class="form-control" style="width: 100px;">
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
            <p class="text-center">Your cart is empty.</p>
        <?php endif; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php include 'footer.php'; ?>
