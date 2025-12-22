<!DOCTYPE html>
<html>
<head>
    <title>Login - Top Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 col-md-4">
    <h3 class="text-center text-primary">Login Admin</h3>

    @if ($errors->any())
        <div class="alert alert-danger">Login gagal</div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST">
        @csrf
        <input type="email" name="email" class="form-control mb-2" placeholder="Email">
        <input type="password" name="password" class="form-control mb-2" placeholder="Password">
        <button class="btn btn-primary w-100">Login</button>
    </form>

    <p class="mt-3 text-center">
        Belum punya akun? <a href="/register">Register</a>
    </p>
</div>

</body>
</html>
