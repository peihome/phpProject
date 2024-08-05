<?php 
include 'header.php';

$product_id = $_GET['id'] ?? 0;
$product = getProductById($product_id);

if (!$product) {
    echo '<h2 class="text-center">Product not found</h2>';
    include 'footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = 1;
    
    try {
        $quantity = intval($_POST['quantity']);
    }
    catch(Exception $e) {
        
    }

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
    
    updateCart($_SESSION['cart']);

    header('Location: cart.php');
    exit;
}
?>

<body>
    <div class="container mt-5 product">
        <div class="row">
            <div class="col-md-7">
                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
            <div class="col-md-4">
                <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                <p><?php echo htmlspecialchars($product['description']); ?></p>
                <p><strong>$<?php echo number_format($product['price'], 2); ?> per lb</strong></p>
                <form action="" method="post">
                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                    <div class="form-group quantitySpinner">
                        <label for="quantity">Quantity (in lbs):</label>
                        <input type="number" name="quantity" id="quantity" min="1" step="1" value="1" class="form-control mb-3" style="width: 100px;">
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg custom-button1">Add to Cart</button>
                </form>
            </div>
        </div>
    </div>
</body>

<?php include 'footer.php'; ?>
