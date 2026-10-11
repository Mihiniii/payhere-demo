<!DOCTYPE html>
<html>
<head>
    <title>PayHere Demo</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; display: flex; justify-content: center; padding-top: 60px; }
        .card { background: white; padding: 30px; border-radius: 10px; width: 300px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        button { background: #1a73e8; color: white; border: none; padding: 12px; width: 100%; border-radius: 6px; cursor: pointer; font-size: 16px; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Wireless Headphones</h2>
        <p>Price: <strong>LKR 2,500.00</strong></p>
        <form action="checkout.php" method="post">
            <input type="hidden" name="product_id" value="1">
            <button type="submit">Buy Now</button>
        </form>
    </div>
</body>
</html>