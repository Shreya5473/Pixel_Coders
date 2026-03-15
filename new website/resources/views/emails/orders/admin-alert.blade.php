<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Order Alert</title>
</head>
<body>
    <h2>New Order Received</h2>

    <p>Order <strong>#{{ $order->increment_id }}</strong> has been placed.</p>

    <p>Customer: {{ $order->customer_full_name }} ({{ $order->customer_email }})</p>

    <p>Total: {{ core()->formatPrice($order->grand_total, $order->order_currency_code) }}</p>
</body>
</html>
