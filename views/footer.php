<!-- Footer -->
<?php if(isset($_SESSION['message'])) { ?>

    <div class="alert alert-primary role="alert">
        <?= $_SESSION['message'] ?>
    </div>

<?php 
    unset($_SESSION['message']);
} ?>

<footer class="bg-dark text-white py-4">
    <div class="nav-font">
        <div class="container text-center">
            <p>&copy; 2024 Shop N Go. All rights reserved.</p>

            <ul class="list-inline mb-3">
                <div class="nav-font">
                    <li class="list-inline-item"><a href="privacy_policy.php" class="text-white">Privacy Policy</a></li>
                    <li class="list-inline-item"><a href="terms_of_service.php" class="text-white">Terms of Service</a>
                    </li>
                    <li class="list-inline-item"><a href="faq.php" class="text-white">FAQ</a></li>
                </div>
            </ul>
        </div>
    </div>
</footer>

<!-- Include JS Libraries -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>