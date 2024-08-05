<?php include 'header.php'; ?>

<div class="container mt-5">
    <h1 class="text-center">Our Products</h1>

    <?php
    if (isset($_SESSION['order_success'])) {
        echo '<div class="alert alert-success text-center">' . htmlspecialchars($_SESSION['order_success']) . '</div>';
        unset($_SESSION['order_success']);
    }

    // Function to get all products or filter by category
    function filterProducts($category = null) {
        $products = getAllProducts();

        if ($category) {
            return array_filter($products, function ($product) use ($category) {
                return $product['category'] === $category;
            });
        }
        return $products;
    }

    // Check if a category filter is set
    $selected_category = isset($_GET['category']) ? $_GET['category'] : null;
    $products = filterProducts($selected_category);

    function display_products($products) {
        echo '<div class="row mt-4">';
        foreach ($products as $product) {
            // Price per lb in CAD
            $pricePerLb = number_format($product['price'], 2);

            echo '
            <div class="col-md-3 mb-4">
                <div class="card bhu">
                    <img src="' . htmlspecialchars($product['image_url']) . '" class="card-img-top" alt="' . htmlspecialchars($product['name']) . '">
                    <div class="card-body">
                        <h5 class="card-title">' . htmlspecialchars($product['name']) . '</h5>
                        <p class="card-text">' . htmlspecialchars($product['description']) . '</p>
                        <p class="card-text"><strong>$' . $pricePerLb . ' per lb</strong></p>
                        <a href="productDetails.php?id=' . htmlspecialchars($product['product_id']) . '" class="btn btn-primary btn-lg custom-button1">View Details</a>
                    </div>
                </div>
            </div>
            ';
        }
        echo '</div>';
    }
    ?>

    <!-- Filter Form -->
    <form method="GET" class="mb-4 categoryForm">
        <div class="form-group">
            <label for="category">Filter by Category:</label>
            <select name="category" id="category" class="form-control" onchange="this.form.submit()">
                <option value="">All</option>
                <option value="Vegetables" <?php if ($selected_category == 'Vegetables') echo 'selected'; ?>>Vegetables</option>
                <option value="Fruits" <?php if ($selected_category == 'Fruits') echo 'selected'; ?>>Fruits</option>
                <option value="Dairy" <?php if ($selected_category == 'Dairy') echo 'selected'; ?>>Dairy</option>
                <!-- Add more categories as needed -->
            </select>
        </div>
    </form>

    <?php display_products($products); ?>
</div>

<?php include 'footer.php'; ?>