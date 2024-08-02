<?php include 'header.php'; ?>

<div class="container mt-5">
    <h1 class="text-center">Our Products</h1>
    
    <?php
    
    if (isset($_SESSION['order_success'])) {
        echo '<div class="alert alert-success text-center">' . htmlspecialchars($_SESSION['order_success']) . '</div>';
        unset($_SESSION['order_success']);
    }
    
    $products = [
        [
            'id' => 1,
            'name' => 'Apple',
            'price' => 1.50,
            'description' => 'Fresh and juicy apples.',
            'image' => 'images/apple.jpg',
            'category' => 'Fruits',
            'unit' => 'lb' 
        ],
        [
            'id' => 2,
            'name' => 'Banana',
            'price' => 1.00,
            'description' => 'Sweet and ripe bananas.',
            'image' => 'images/banana.jpg',
            'category' => 'Fruits',
            'unit' => 'lb' 
        ],
        [
            'id' => 3,
            'name' => 'Orange',
            'price' => 1.20,
            'description' => 'Citrusy and refreshing oranges.',
            'image' => 'images/orange.jpg',
            'category' => 'Fruits',
            'unit' => 'lb' 
        ],
        [
            'id' => 4,
            'name' => 'Grapes',
            'price' => 1.20,
            'description' => 'Fresh and sweet grapes.',
            'image' => 'images/grapes.jpg',
            'category' => 'Fruits',
            'unit' => 'lb' 
        ],
        [
            'id' => 5,
            'name' => 'Carrot',
            'price' => 0.80,
            'description' => 'Crunchy and healthy carrots.',
            'image' => 'images/carrot.jpg',
            'category' => 'Vegetables',
            'unit' => 'lb' 
        ],
        [
            'id' => 6,
            'name' => 'Lettuce',
            'price' => 0.90,
            'description' => 'Fresh and crispy lettuce.',
            'image' => 'images/lettuce.jpg',
            'category' => 'Vegetables',
            'unit' => 'lb' 
        ],
        [
            'id' => 7,
            'name' => 'Tomato',
            'price' => 0.70,
            'description' => 'Ripe and juicy tomatoes.',
            'image' => 'images/tomato.jpg',
            'category' => 'Vegetables',
            'unit' => 'lb' 
        ],
        [
            'id' => 8,
            'name' => 'Cucumber',
            'price' => 1.10,
            'description' => 'Cool and refreshing cucumbers.',
            'image' => 'images/cucumber.jpg',
            'category' => 'Vegetables',
            'unit' => 'lb' 
        ],
        [
            'id' => 9,
            'name' => 'Milk',
            'price' => 2.00,
            'description' => 'Fresh dairy milk.',
            'image' => 'images/milk.jpg',
            'category' => 'Dairy',
            'unit' => 'lb' 
        ],
        [
            'id' => 10,
            'name' => 'Cheese',
            'price' => 3.50,
            'description' => 'Delicious cheese.',
            'image' => 'images/cheese.jpg',
            'category' => 'Dairy',
            'unit' => 'lb' 
        ],
        [
            'id' => 11,
            'name' => 'Yogurt',
            'price' => 1.50,
            'description' => 'Creamy and healthy yogurt.',
            'image' => 'images/yogurt.jpg',
            'category' => 'Dairy',
            'unit' => 'lb' 
        ],
        [
            'id' => 12,
            'name' => 'Butter',
            'price' => 2.50,
            'description' => 'Rich and creamy butter.',
            'image' => 'images/butter.jpg',
            'category' => 'Dairy',
            'unit' => 'lb' 
        ],
       
    ];

    
    function display_products_by_category($products, $category) {
        echo '<div class="my-4">';
        echo '<h2 class="text-center">' . $category . '</h2>';
        echo '<div class="row mt-4">';

        foreach ($products as $product) {
            if ($product['category'] === $category) {
                // Price per lb in CAD
                $pricePerLb = number_format($product['price'], 2);
                
                echo '
                <div class="col-md-3 mb-4">
                    <div class="card bhu">
                        <img src="' . htmlspecialchars($product['image']) . '" class="card-img-top" alt="' . htmlspecialchars($product['name']) . '">
                        <div class="card-body">
                            <h5 class="card-title">' . htmlspecialchars($product['name']) . '</h5>
                            <p class="card-text">' . htmlspecialchars($product['description']) . '</p>
                            <p class="card-text"><strong>$' . $pricePerLb . ' per lb</strong></p>
                            <a href="productDetails.php?id=' . htmlspecialchars($product['id']) . '" class="btn btn-primary btn-lg custom-button1">View Details</a>
                        </div>
                    </div>
                </div>
                ';
            }
        }

        echo '</div>';
        echo '</div>';
    }

    display_products_by_category($products, 'Vegetables');
    display_products_by_category($products, 'Fruits');
    display_products_by_category($products, 'Dairy');

    ?>

</div>

<?php include 'footer.php'; ?>
