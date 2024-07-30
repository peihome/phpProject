<?php include 'header.php'; ?>

<div class="container mt-5">
    <h1 class="text-center">Our Products</h1>
    <div class="row mt-4">
        <?php
        // Mock data for products. Replace this with your database query.
        $products = [
            [
                'id' => 1,
                'name' => 'Apple',
                'price' => 1.50,
                'description' => 'Fresh and juicy apples.',
                'image' => '../assets/images/apple.jpg'
            ],
            [
                'id' => 2,
                'name' => 'Banana',
                'price' => 1.00,
                'description' => 'Sweet and ripe bananas.',
                'image' => '../assets/images/banana.jpg'
            ],
            [
                'id' => 3,
                'name' => 'Orange',
                'price' => 1.20,
                'description' => 'Citrusy and refreshing oranges.',
                'image' => '../assets/images/orange.jpg'
            ],
            // Add more products as needed.
        ];

        // Display products
        foreach ($products as $product) {
            echo '
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="' . $product['image'] . '" class="card-img-top" alt="' . $product['name'] . '">
                    <div class="card-body">
                        <h5 class="card-title">' . $product['name'] . '</h5>
                        <p class="card-text">' . $product['description'] . '</p>
                        <p class="card-text"><strong>$' . number_format($product['price'], 2) . '</strong></p>
                        <a href="product-details.php?id=' . $product['id'] . '" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
            ';
        }
        ?>
    </div>
</div>

<?php include 'footer.php'; ?>