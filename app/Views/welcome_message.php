<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Selamat Datang!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f8f9fa; /* Light background */
        }
        .card {
            text-align: center;
            padding: 3rem;
            border-radius: 0.75rem; /* Slightly rounded corners */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); /* Softer shadow */
        }
        .card-title {
            font-size: 2.5rem; /* Larger title */
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #343a40; /* Darker text for title */
        }
        .card-text {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            color: #6c757d; /* Muted text for description */
        }
        .btn-login {
            font-size: 1.2rem;
            padding: 0.75rem 2.5rem;
            border-radius: 50px; /* Pill-shaped button */
            background-color: #007bff; /* Primary blue */
            border-color: #007bff;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
        .btn-login:hover {
            background-color: #0056b3; /* Darker blue on hover */
            border-color: #0056b3;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card p-4 shadow">
                    <h1 class="card-title">Selamat Datang!</h1>
                    <p class="card-text">Ini adalah halaman *landing page* aplikasi Anda. Silakan klik tombol di bawah untuk masuk ke sistem.</p>
                    <a href="<?= base_url('auth/login') ?>" class="btn btn-primary btn-login">Login Sekarang</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>