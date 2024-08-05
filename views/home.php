<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop and Go - Home</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <!-- Hero Section -->
    <div class="hero-section text-center text-white">
        <div class="container">
            <h1 class="display-4">Welcome to Shop and Go</h1>
            <p class="lead">Your one-stop shop for all your grocery needs!</p>
            <a href="product.php" class="btn btn-primary btn-lg custom-button1">Browse Products</a>
        </div>
    </div>

    <!-- Featured Categories -->
    <div class="container my-5">
        <h2 class="text-center mb-4">Featured Categories</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="../images/vegetables.jpg" class="card-img-top" alt="Vegetables">
                    <div class="card-body">
                        <h5 class="card-title">Vegetables</h5>
                        <p class="card-text">Fresh and organic vegetables for a healthy lifestyle.</p>
                        <a href="product.php?category=Vegetables" class="btn btn-success">Shop Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="../images/fruits.jpg" class="card-img-top" alt="Fruits">
                    <div class="card-body">
                        <h5 class="card-title">Fruits</h5>
                        <p class="card-text">A variety of fresh fruits packed with vitamins.</p>
                        <a href="product.php?category=Fruits" class="btn btn-success">Shop Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="../images/dairy.jpg" class="card-img-top" alt="Dairy">
                    <div class="card-body">
                        <h5 class="card-title">Dairy</h5>
                        <p class="card-text">High-quality dairy products for your daily needs.</p>
                        <a href="product.php?category=Dairy" class="btn btn-success">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<?php include 'footer.php'; ?>