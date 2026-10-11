<?php
$host = 'localhost';
$db   = 'payhere_demo';
$user = 'root';
$pass = '';          // XAMPP default: password එක හිස්

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}