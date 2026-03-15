<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
</head>
<body>
    <h2>Thank you for your order!</h2>

    <p>Hello {{ $order->customer_full_name }},</p>

    <p>Your order <strong>#{{ $order->increment_id }}</strong> has been placed successfully.</p>

    <p>Total: {{ core()->formatPrice($order->grand_total, $order->order_currency_code) }}</p>

    <p>We will notify you once your order is processed.</p>
</body>
</html>
