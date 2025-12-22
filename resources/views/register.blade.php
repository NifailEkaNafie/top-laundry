<!DOCTYPE html>
<html>
<head>
    <title>Register - Top Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 col-md-4">
    <h3 class="text-center text-primary">Register Admin</h3>

    @if ($errors->any())
        <div class="alert alert-danger">Register gagal</div>
    @endif

    <form method="POST">
        @csrf
        <input type="text" name="name" class="form-control mb-2" placeholder="Nama">
        <input type="email" name="email" class="form-control mb-2" placeholder="Email">
        <input type="password" name="password" class="form-control mb-2" placeholder="Password">
        <input type="password" name="password_confirmation" class="form-control mb-2" placeholder="Konfirmasi Password">
        <button class="btn btn-primary w-100">Register</button>
    </form>

    <p class="mt-3 text-center">
        Sudah punya akun? <a href="/login">Login</a>
    </p>
</div>

</body>
</html>
