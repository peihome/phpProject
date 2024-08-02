<?php
session_start();

// Mock data for products. Replace this with your database query.
$products = [
    [
        'id' => 1,
        'name' => 'Apple',
        'price' => 1.50,
        'description' => 'Fresh and juicy apples.',
        'image' => '../images/apple.jpg',
        'category' => 'Fruits'
    ],
    [
        'id' => 2,
        'name' => 'Banana',
        'price' => 1.00,
        'description' => 'Sweet and ripe bananas.',
        'image' => '../images/banana.jpg',
        'category' => 'Fruits'
    ],
    [
        'id' => 3,
        'name' => 'Orange',
        'price' => 1.20,
        'description' => 'Citrusy and refreshing oranges.',
        'image' => '../images/orange.jpg',
        'category' => 'Fruits'
    ],
    [
        'id' => 4,
        'name' => 'Grapes',
        'price' => 1.20,
        'description' => 'Fresh and sweet grapes.',
        'image' => '../images/grapes.jpg',
        'category' => 'Fruits'
    ],
    [
        'id' => 5,
        'name' => 'Carrot',
        'price' => 0.80,
        'description' => 'Crunchy and healthy carrots.',
        'image' => '../images/carrot.jpg',
        'category' => 'Vegetables'
    ],
    [
        'id' => 6,
        'name' => 'Lettuce',
        'price' => 0.90,
        'description' => 'Fresh and crispy lettuce.',
        'image' => '../images/lettuce.jpg',
        'category' => 'Vegetables'
    ],
    [
        'id' => 7,
        'name' => 'Tomato',
        'price' => 0.70,
        'description' => 'Ripe and juicy tomatoes.',
        'image' => '../images/tomato.jpg',
        'category' => 'Vegetables'
    ],
    [
        'id' => 8,
        'name' => 'Cucumber',
        'price' => 1.10,
        'description' => 'Cool and refreshing cucumbers.',
        'image' => '../images/cucumber.jpg',
        'category' => 'Vegetables'
    ],
    [
        'id' => 9,
        'name' => 'Milk',
        'price' => 2.00,
        'description' => 'Fresh dairy milk.',
        'image' => '../images/milk.jpg',
        'category' => 'Dairy'
    ],
    [
        'id' => 10,
        'name' => 'Cheese',
        'price' => 3.50,
        'description' => 'Delicious cheese.',
        'image' => '../images/cheese.jpg',
        'category' => 'Dairy'
    ],
    [
        'id' => 11,
        'name' => 'Yogurt',
        'price' => 1.50,
        'description' => 'Creamy and healthy yogurt.',
        'image' => '../images/yogurt.jpg',
        'category' => 'Dairy'
    ],
    [
        'id' => 12,
        'name' => 'Butter',
        'price' => 2.50,
        'description' => 'Rich and creamy butter.',
        'image' => '../images/butter.jpg',
        'category' => 'Dairy'
    ],
    
];

$product_id = $_GET['id'] ?? 0;
$product = null;

foreach ($products as $p) {
    if ($p['id'] == $product_id) {
        $product = $p;
        break;
    }
}

if (!$product) {
    echo '<h2 class="text-center">Product not found</h2>';
    include 'footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = $_POST['quantity'] ?? 1;

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    header('Location: cart.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Product Details</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container mt-5 product">
        <div class="row">
            <div class="col-md-6">
                <img src="<?php echo htmlspecialchars($product['image']); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
            <div class="col-md-6">
                <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                <p><?php echo htmlspecialchars($product['description']); ?></p>
                <p><strong>$<?php echo number_format($product['price'], 2); ?> per lb</strong></p>
                <form action="" method="post">
                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                    <div class="form-group">
                        <label for="quantity">Quantity (in lbs):</label>
                        <input type="number" name="quantity" id="quantity" min="0.1" step="0.1" value="1" class="form-control mb-3" style="width: 100px;">
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg custom-button1">Add to Cart</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php include 'footer.php'; ?>
