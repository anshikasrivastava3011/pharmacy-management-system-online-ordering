<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <style>
        body { background: #f4f6f9; }
        .card { border: none; border-radius: 16px; }
        .icon-circle {
            width: 72px; height: 72px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem; margin: 0 auto 1.2rem;
        }
        .icon-success { background: #d4edda; color: #198754; }
        .icon-warning { background: #fff3cd; color: #856404; }
        .icon-info    { background: #d1ecf1; color: #0c5460; }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="min-height:100vh;">
        <div class="card shadow-lg p-5 text-center" style="max-width:480px; width:100%;">

            <h5 class="mb-4 text-muted">Hello, {{ $order->user->name }}!</h5>

            @if ($state === 'Confirmednow')
                <div class="icon-circle icon-success mx-auto"><span>&#10003;</span></div>
                <h4 class="text-success mb-2">Order Confirmed!</h4>
                <p class="text-muted">Your order <strong>#{{ $order->id }}</strong> has been successfully confirmed. The pharmacy will now prepare it for delivery.</p>

            @elseif ($state === 'Confirmed')
                <div class="icon-circle icon-info mx-auto"><span>&#8505;</span></div>
                <h4 class="text-info mb-2">Already Confirmed</h4>
                <p class="text-muted">Your order <strong>#{{ $order->id }}</strong> is already confirmed. No action needed.</p>

            @elseif ($state === 'Canceled')
                <div class="icon-circle icon-warning mx-auto"><span>&#33;</span></div>
                <h4 class="text-warning mb-2">Order Cancelled</h4>
                <p class="text-muted">Your order <strong>#{{ $order->id }}</strong> has already been cancelled and cannot be confirmed.</p>

            @elseif ($state === 'Delivered')
                <div class="icon-circle icon-info mx-auto"><span>&#8505;</span></div>
                <h4 class="text-info mb-2">Already Delivered</h4>
                <p class="text-muted">Your order <strong>#{{ $order->id }}</strong> has already been delivered.</p>

            @else
                <div class="icon-circle icon-warning mx-auto"><span>&#33;</span></div>
                <h4 class="text-secondary mb-2">Nothing to Confirm</h4>
                <p class="text-muted">This order is currently <strong>{{ $order->status }}</strong> and is not awaiting your confirmation.</p>
            @endif

            <hr class="my-4">
            <p class="text-muted small mb-0">Thank you for using our Pharmacy System.</p>
        </div>
    </div>
</body>
</html>
