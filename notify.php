<?php
$cfg = require 'config.php';
require 'db.php';

// Debug කරන්න ලැබෙන හැම දෙයක්ම log කරනවා
file_put_contents('notify.log', date('Y-m-d H:i:s') . ' ' . print_r($_POST, true) . "\n", FILE_APPEND);

$merchant_id = $_POST['merchant_id']      ?? '';
$order_id    = $_POST['order_id']         ?? '';
$amount      = $_POST['payhere_amount']   ?? '';
$currency    = $_POST['payhere_currency'] ?? '';
$status_code = $_POST['status_code']      ?? '';
$md5sig      = $_POST['md5sig']           ?? '';
$payment_id  = $_POST['payment_id']       ?? null;

// 1. Signature එක verify කරනවා
$local_sig = strtoupper(md5(
    $merchant_id .
    $order_id .
    $amount .
    $currency .
    $status_code .
    strtoupper(md5($cfg['merchant_secret']))
));

if ($local_sig !== $md5sig || $merchant_id !== $cfg['merchant_id']) {
    http_response_code(400);
    exit('Invalid signature');
}

// 2. Order එක DB එකේ තියෙනවද, amount එක සමානද කියලා බලනවා
$stmt = $pdo->prepare('SELECT amount, currency FROM orders WHERE order_id = ?');
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order || $order['amount'] != $amount || $order['currency'] !== $currency) {
    http_response_code(400);
    exit('Order mismatch');
}

// 3. Status එක update කරනවා
$statuses = [
    '2'  => 'PAID',
    '0'  => 'PENDING',
    '-1' => 'CANCELED',
    '-2' => 'FAILED',
    '-3' => 'CHARGEDBACK',
];
$status = $statuses[$status_code] ?? 'UNKNOWN';

$stmt = $pdo->prepare('UPDATE orders SET status = ?, payment_id = ? WHERE order_id = ?');
$stmt->execute([$status, $payment_id, $order_id]);

echo 'OK';