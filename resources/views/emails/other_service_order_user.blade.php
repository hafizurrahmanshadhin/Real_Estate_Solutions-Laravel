<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
</head>

<body>
    <h2>Hello {{ $order->first_name }} {{ $order->last_name }},</h2>
    <p>Thank you for placing your order with us!</p>
    <p>
        <strong>Service:</strong> {{ $order->otherService->title ?? 'Service' }}<br>
        <strong>Order ID:</strong> {{ $order->id }}<br>
        <strong>Address:</strong> {{ $order->full_address }}<br>
        <strong>Footage Size:</strong> {{ $order->footageSize->size ?? '-' }}
    </p>
    @if ($order->additional_info)
        <p><strong>Additional Info:</strong> {{ $order->additional_info }}</p>
    @endif
    <p>We have received your request and will contact you soon to confirm the details.</p>
    <br>
    <p>Best regards,<br>
        The {{ config('app.name') }} Team</p>
</body>

</html>
