<?php
$cfg = require 'config.php';
require 'db.php';

// 1. Product එක server side එකෙන් ගන්නවා (මිල කවදාවත් browser එකෙන් ගන්න එපා)
$products = [
    1 => ['name' => 'Wireless Headphones', 'price' => 2500.00],
];
$id = (int)($_POST['product_id'] ?? 0);
if (!isset($products[$id])) {
    die('Invalid product');
}
$item     = $products[$id]['name'];
$amount   = number_format($products[$id]['price'], 2, '.', '');
$currency = 'LKR';

// 2. Unique order ID එකක් හදලා DB එකේ PENDING විදිහට save කරනවා
$order_id = 'ORD' . time() . rand(100, 999);
$stmt = $pdo->prepare('INSERT INTO orders (order_id, item, amount, currency) VALUES (?, ?, ?, ?)');
$stmt->execute([$order_id, $item, $amount, $currency]);

// 3. Hash එක හදනවා (server side එකේ විතරයි!)
$hash = strtoupper(md5(
    $cfg['merchant_id'] .
    $order_id .
    $amount .
    $currency .
    strtoupper(md5($cfg['merchant_secret']))
));
?>
<!DOCTYPE html>
<html>
<body onload="document.forms[0].submit()">
    <p>Redirecting to PayHere...</p>
    <form method="post" action="<?= $cfg['checkout_url'] ?>">
        <input type="hidden" name="merchant_id" value="<?= $cfg['merchant_id'] ?>">
        <input type="hidden" name="return_url"  value="<?= $cfg['local_url'] ?>/return.php?order_id=<?= $order_id ?>">
        <input type="hidden" name="cancel_url"  value="<?= $cfg['local_url'] ?>/cancel.php">
        <input type="hidden" name="notify_url"  value="<?= $cfg['public_url'] ?>/notify.php">

        <input type="hidden" name="order_id" value="<?= $order_id ?>">
        <input type="hidden" name="items"    value="<?= htmlspecialchars($item) ?>">
        <input type="hidden" name="currency" value="<?= $currency ?>">
        <input type="hidden" name="amount"   value="<?= $amount ?>">

        <!-- Demo customer විස්තර -->
        <input type="hidden" name="first_name" value="Test">
        <input type="hidden" name="last_name"  value="User">
        <input type="hidden" name="email"      value="test@example.com">
        <input type="hidden" name="phone"      value="0771234567">
        <input type="hidden" name="address"    value="No. 1, Galle Road">
        <input type="hidden" name="city"       value="Colombo">
        <input type="hidden" name="country"    value="Sri Lanka">

        <input type="hidden" name="hash" value="<?= $hash ?>">
    </form>
</body>
</html>