<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 30px;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            max-width: 700px;
            margin: auto;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        h1 {
            color: #28a745;
        }

        ul {
            padding-left: 20px;
        }

        li {
            margin-bottom: 10px;
        }

        .status {
            display: inline-block;
            background: #fff3cd;
            color: #856404;
            padding: 8px 14px;
            border-radius: 8px;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            color: #666;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Hello {{ $notifiable->name }}!</h1>

    <p>Your medicine order has been placed successfully.</p>

    <p>Your order is currently:</p>

    <p>
        <span class="status">{{ $order->status }}</span>
    </p>

    <h3>Order Details</h3>

    <ul>
        <li><strong>Order ID:</strong> {{ $order->id }}</li>
        <li><strong>Pharmacy:</strong> {{ $order->pharmacy->pharmacy_name }}</li>
        <li><strong>Delivery Area:</strong> {{ $order->address->area->name }}</li>
        <li><strong>Total Price:</strong> ₹{{ $order->price }}</li>
    </ul>

    <h3>Medicines Ordered</h3>

    <ul>
        @foreach ($order->medicines as $medicine)
            <li>
                {{ $medicine->name ?? $medicine->commercial_name ?? $medicine->scientific_name }}
                — Quantity: {{ $medicine->pivot->quantity }}
            </li>
        @endforeach
    </ul>

    <div class="footer">
        <p>
            Thank you for choosing our pharmacy.
            Our team will process your order shortly.
        </p>
    </div>
</div>

</body>
</html>