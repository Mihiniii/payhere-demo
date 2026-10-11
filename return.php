<?php
require 'db.php';

$order_id = $_GET['order_id'] ?? '';
$stmt = $pdo->prepare('SELECT * FROM orders WHERE order_id = ?');
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    die('Order not found');
}
$status = $order['status'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment Status</title>
    <?php if ($status === 'PENDING'): ?>
        <meta http-equiv="refresh" content="3">
    <?php endif; ?>
    <style>
        body { font-family: Arial; background: #f4f4f4; display: flex; justify-content: center; padding-top: 60px; }
        .card { background: white; padding: 30px; border-radius: 10px; width: 320px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .paid { color: #188038; } .pending { color: #e37400; } .failed { color: #d93025; }
    </style>
</head>
<body>
<div class="card">
    <?php if ($status === 'PAID'): ?>
        <h2 class="paid">✅ Payment Successful</h2>
    <?php elseif ($status === 'PENDING'): ?>
        <h2 class="pending">⏳ Processing payment...</h2>
        <p>This page will refresh automatically.</p>
    <?php else: ?>
        <h2 class="failed">❌ Payment <?= htmlspecialchars($status) ?></h2>
    <?php endif; ?>

    <p>Order ID: <?= htmlspecialchars($order['order_id']) ?></p>
    <p>Item: <?= htmlspecialchars($order['item']) ?></p>
    <p>Amount: LKR <?= htmlspecialchars($order['amount']) ?></p>
    <?php if ($order['payment_id']): ?>
        <p>Payment ID: <?= htmlspecialchars($order['payment_id']) ?></p>
    <?php endif; ?>
    <a href="index.php">Back to shop</a>
</div>
</body>
</html>