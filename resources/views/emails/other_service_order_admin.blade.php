<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>New Service Order Received</title>
</head>

<body>
    <h2>New Service Order Received</h2>
    <p>
        <strong>Name:</strong> {{ $order->first_name }} {{ $order->last_name }}<br>
        <strong>Email:</strong> {{ $order->email }}<br>
        <strong>Phone:</strong> {{ $order->phone_number }}<br>
        <strong>Service:</strong> {{ $order->otherService->title ?? 'Service' }}<br>
        <strong>Address:</strong> {{ $order->full_address }}<br>
        <strong>Footage Size:</strong> {{ $order->footageSize->size ?? '-' }}
    </p>
    @if ($order->additional_info)
        <p><strong>Additional Info:</strong> {{ $order->additional_info }}</p>
    @endif
    <p>Please review and process this order as soon as possible.</p>
    <br>
    <p>Regards,<br>
        {{ config('app.name') }} System</p>
</body>

</html>
