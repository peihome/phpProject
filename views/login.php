<?php 
session_start();
$_SESSION['page'] = 'Login';
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $userId = loginUser($email, $password);
    if (is_int($userId)) {
        $_SESSION['email'] = $email;
        $_SESSION['userId'] = $userId;
        $user = getUserByUserId($userId);
        $_SESSION['message'] = "Welcome " . $user['first_name'] . "!";
        if($_SESSION['redirect_url']){
            header('Location: ' . $_SESSION['redirect_url']);
        }else {
            header('Location: product.php');
        }
        exit;
    }else {
        $_SESSION['message'] = $login_response;
    }

}
?>

<body>
    <div class="container mt-5">
        <h1>Login</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo $_POST['email'] ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" value="<?php echo $_POST['password'] ?>" required>
            </div>
            <button type="submit" class="btn btn-primary btn-lg custom-button1">Login</button>
        </form>
        <div class="product">
            <p>Don't have an account? <a href="register.php">Register here</a>.</p>
        </div>
    </div>
</body>

<?php include 'footer.php'; ?>
