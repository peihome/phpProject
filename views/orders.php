<?php
include 'header.php';

// Fetch past orders
$orders = getOrdersByUserId();

?>

<div class="container mt-5 orders">
    <h1 class="text-center">Past Orders</h1>

    <?php if (empty($orders)): ?>
        <div class="alert alert-info text-center">No past orders found.</div>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <div>
                        <h5>Order ID: <?= htmlspecialchars($order['order_id']); ?></h5>
                        <p>Date: <?= htmlspecialchars($order['created_at']); ?></p>
                        <p>Total Price: $<?= number_format($order['total_price'], 2); ?></p>
                        <p>Status: <?= htmlspecialchars($order['status']); ?></p>
                    </div>
                    <div class="center">
                        <a target="_blank" href="generate_invoice.php?order_id=<?= htmlspecialchars($order['order_id']); ?>"
                            class="btn btn-secondary mt-3">Get Invoice</a>
                    </div>
                </div>
                <div class="card-body">
                    <h6>Order Items:</h6>
                    <ul class="list-group">
                        <?php
                        $orderItems = getOrderItemsByOrderId($order['order_id']);

                        $productIds = [];
                        foreach ($orderItems as $item) {
                            $productIds[] = $item['product_id'];
                        }
                        $products = getProducts($productIds);

                        foreach ($orderItems as $item): ?>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-md-2">
                                        <img src="<?= htmlspecialchars($products[$item['product_id']]['image_url']); ?>" class="img-fluid"
                                            alt="<?= htmlspecialchars($products[$item['product_id']]['name']); ?>">
                                    </div>
                                    <div class="col-md-10">
                                        <p><strong><?= htmlspecialchars($products[$item['product_id']]['name']); ?></strong></p>
                                        <p>Quantity: <?= htmlspecialchars($item['quantity']); ?></p>
                                        <p>Price: $<?= number_format($item['price'], 2); ?></p>
                                        <p>Description: <?= htmlspecialchars($products[$item['product_id']]['description']); ?></p>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>