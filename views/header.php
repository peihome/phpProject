<?php
session_start();
require_once('../controllers/Utils.php');
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop N Go</title>
    <link rel="icon" type="image/x-icon" href="../images/logo.jpg">
    <link rel="stylesheet" href="../styles/style.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="../js/script.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <!-- Navbar -->
    <div class="nav-font">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">

            <a class="navbar-brand" href="home.php">
                <img src="../images/logo.jpg" alt="Shop N Go" class="logo" id="navbar-logo"> Shop N Go</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="nav-font">
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item <?php echo $_SESSION['page'] == 'Home' ? 'active' : '' ?>">
                            <a class="nav-link" href="home.php">Home</a>
                        </li>
                        <li class="nav-item <?php echo $_SESSION['page'] == 'Product' ? 'active' : '' ?>">
                            <a class="nav-link" href="product.php">Products</a>
                        </li>
                        <li class="nav-item <?php echo $_SESSION['page'] == 'Cart' ? 'active' : '' ?>">
                            <a class="nav-link" href="cart.php">Cart</a>
                        </li>
                        <li class="nav-item <?php echo $_SESSION['page'] == 'Checkout' ? 'active' : '' ?>">
                            <a class="nav-link" href="checkout.php">Checkout</a>
                        </li>
                        <li class="nav-item <?php echo $_SESSION['page'] == 'Orders' ? 'active' : '' ?>">
                            <a class="nav-link" href="orders.php">Orders</a>
                        </li>

                        <?php if($_SESSION['userId']) { ?>

                            <li class="nav-item <?php echo $_SESSION['page'] == 'Logout' ? 'active' : '' ?>">
                                <a class="nav-link" href="logout.php">Logout</a>
                            </li>

                        <?php } else { ?>

                            <li class="nav-item <?php echo $_SESSION['page'] == 'Login' ? 'active' : '' ?>">
                                <a class="nav-link" href="login.php">Login</a>
                            </li>

                        <?php } ?>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</body>