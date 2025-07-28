<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .section {
            margin-bottom: 1.5rem;
        }

        .section h2 {
            margin-bottom: .5rem;
            border-bottom: 1px solid #ddd;
            padding-bottom: .25rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }

        th,
        td {
            padding: .5rem;
            border: 1px solid #ddd;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .total {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Invoice #{{ $order->id }}</h1>
        <p>Issued: {{ $order->created_at->format('F j, Y') }}</p>
    </div>

    <div class="section">
        <h2>Billing To</h2>
        <p>
            {{ $order->first_name }} {{ $order->last_name }}<br>
            {{ $order->email }}<br>
            {{ $order->phone_number }}
        </p>
    </div>

    <div class="section">
        <h2>Property & Appointment</h2>
        <p>
            <strong>Address:</strong><br>
            {{ $order->properties->first()->address }}<br>
            {{ $order->properties->first()->city }}, {{ $order->properties->first()->state }}
            {{ $order->properties->first()->zip_code }}<br>
            <strong>Type:</strong> {{ $order->properties->first()->property_type }}<br>
            <strong>Footage Size:</strong> {{ $order->properties->first()->footageSize->size }}
        </p>
        <p>
            <strong>Appointment:</strong><br>
            {{ $order->appointments->first()->date->format('F j, Y') }} at
            {{ \Carbon\Carbon::parse($order->appointments->first()->time)->format('g:i A') }}
        </p>
    </div>

    <div class="section">
        <h2>Order Details</h2>
        <table>
            <thead>
                <tr>
                    <th>Service / Add‑On</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Quantity</th>
                    <th class="text-right">Line Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>
                            @if ($item->itemable_type === \App\Models\Service::class)
                                {{ $item->itemable->serviceItems->pluck('service_name')->join(', ') }}
                            @else
                                {{ $item->itemable->serviceItem->service_name }}
                                @if ($item->itemable->isCommunityImages())
                                    ({{ $item->itemable->locations }} locations)
                                @endif
                            @endif
                        </td>
                        <td class="text-right">${{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">${{ number_format($item->line_total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right total">Total Paid:</td>
                    <td class="text-right total">${{ number_format($order->total_amount, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <p>Thank you for your business! If you have any questions, reply to this email.</p>
</body>

</html>
