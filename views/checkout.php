<?php
session_start();
$_SESSION['page'] = 'Checkout';
include 'header.php';

if (!isset($_SESSION['email'])) {
    $_SESSION['message'] = "Please login!";
    $_SESSION['redirect_url'] = "checkout.php";
    header('Location: login.php');
    exit;
}

populateCart();

$cart = $_SESSION['cart'];
$productIds = [];
foreach ($cart as $key => $item) {
    $productIds[] = $key;
}

$products = getProducts($productIds);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Input validation
    $street = htmlspecialchars(trim($_POST['street'] ?? ''));
    $city = htmlspecialchars(trim($_POST['city'] ?? ''));
    $state = htmlspecialchars(trim($_POST['state'] ?? ''));
    $postal_code = htmlspecialchars(trim($_POST['postal_code'] ?? ''));
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
    $payment_method = htmlspecialchars(trim($_POST['payment_method'] ?? ''));

    if (empty($street)) {
        $errors[] = 'Street address is required.';
    }

    if (empty($city)) {
        $errors[] = 'City is required.';
    }

    if (empty($state)) {
        $errors[] = 'State is required.';
    }

    if (!preg_match('/^[A-Za-z]\d[A-Za-z] \d[A-Za-z]\d$/', $postal_code)) {
        $errors[] = 'Postal code must be in the format A1A 1A1.';
    }

    if (!preg_match('/^\+1 \d{3} \d{3} \d{4}$/', $phone)) {
        $errors[] = 'Phone number must be in the format +1 XXX XXX XXXX.';
    }

    if (empty($payment_method)) {
        $errors[] = 'Payment method is required.';
    }

    if (empty($errors)) {
        // Save order details here or process payment
        $shipping_address = [
            'street' => $street,
            'city' => $city,
            'state' => $state,
            'postal_code' => $postal_code,
            'country' => 'Canada',
            'phone' => $phone
        ];

        placeOrder($shipping_address);
        unset($_SESSION['cart']);
        unset($_SESSION['cartId']);
        $_SESSION['message'] = "Order successfully placed!";

        header('Location: orders.php');
        exit;
    }
}

$address = getAddressByUserId();
$phone = getPhoneByUserId();

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

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
            <form method="post">
                <div class="row">
                    <div class="col-md-6">
                        <h3>Shipping Information</h3>
                        <div class="form-group">
                            <label for="street">Street</label>
                            <input type="text" id="street" name="street" class="form-control" value="<?= htmlspecialchars($address['street']) ?>" required maxlength="50">
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="city">City</label>
                                <input type="text" id="city" name="city" class="form-control" value="<?= htmlspecialchars($address['city']) ?>" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="state">State</label>
                                <select id="state" name="state" class="form-control" required>
                                    <option value="">Select a state</option>
                                    <option value="AB" <?= $address['state'] == 'AB' ? 'selected' : '' ?>>Alberta</option>
                                    <option value="BC" <?= $address['state'] == 'BC' ? 'selected' : '' ?>>British Columbia</option>
                                    <option value="MB" <?= $address['state'] == 'MB' ? 'selected' : '' ?>>Manitoba</option>
                                    <option value="NB" <?= $address['state'] == 'NB' ? 'selected' : '' ?>>New Brunswick</option>
                                    <option value="NL" <?= $address['state'] == 'NL' ? 'selected' : '' ?>>Newfoundland and Labrador</option>
                                    <option value="NS" <?= $address['state'] == 'NS' ? 'selected' : '' ?>>Nova Scotia</option>
                                    <option value="ON" <?= $address['state'] == 'ON' ? 'selected' : '' ?>>Ontario</option>
                                    <option value="PE" <?= $address['state'] == 'PE' ? 'selected' : '' ?>>Prince Edward Island</option>
                                    <option value="QC" <?= $address['state'] == 'QC' ? 'selected' : '' ?>>Quebec</option>
                                    <option value="SK" <?= $address['state'] == 'SK' ? 'selected' : '' ?>>Saskatchewan</option>
                                    <option value="NT" <?= $address['state'] == 'NT' ? 'selected' : '' ?>>Northwest Territories</option>
                                    <option value="NU" <?= $address['state'] == 'NU' ? 'selected' : '' ?>>Nunavut</option>
                                    <option value="YT" <?= $address['state'] == 'YT' ? 'selected' : '' ?>>Yukon</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="postal_code">Postal Code</label>
                                <input type="text" id="postal_code" name="postal_code" class="form-control" placeholder="A1A 1A1" pattern="[A-Za-z]\d[A-Za-z] \d[A-Za-z]\d" value="<?= htmlspecialchars($address['postal_code']) ?>" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="country">Country</label>
                                <input type="text" id="country" name="country" class="form-control" value="Canada" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="text" id="phone" name="phone" class="form-control" placeholder="+1 XXX XXX XXXX" pattern="\+1 \d{3} \d{3} \d{4}" value="<?= htmlspecialchars($phone) ?>" required>
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
                                        <td><img src="<?php echo htmlspecialchars($product['image_url']); ?>" class="img-fluid" style="height: 100px;"></td>
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

<?php include 'footer.php'; ?>