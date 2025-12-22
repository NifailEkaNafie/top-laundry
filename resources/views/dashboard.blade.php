<!DOCTYPE html>
<html>
<head>
    <title>Dashboard | Top Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

<h3>🧺 Dashboard Orders</h3>

<table class="table table-bordered mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Layanan</th>
            <th>Berat</th>
            <th>Total</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $order)
        <tr>
            <td>{{ $order['id'] }}</td>
            <td>{{ $order['customer_name'] }}</td>
            <td>{{ $order['service_type'] }}</td>
            <td>{{ $order['weight_kg'] }} Kg</td>
            <td>Rp {{ number_format($order['price_total']) }}</td>
            <td>{{ $order['status'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
