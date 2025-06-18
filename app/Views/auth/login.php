<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card p-4 shadow">
                    <h4 class="mb-4 text-center">Login Form</h4>
                    <?php if (session('error')): ?>
                        <div class="alert alert-danger">
                            <?= session('error') ?>
                        </div>
                    <?php endif; ?>
                    <?php if (session('success')): ?>
                        <div class="alert alert-success">
                            <?= session('success') ?>
                        </div>
                    <?php endif; ?>
                    <form action="<?= base_url('/auth/login') ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="form-control"
                                placeholder="Enter your email"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control"
                                placeholder="Enter your password"
                                required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Login
                        </button>

                        <div class="text-center mt-3">
                            <a href="<?= base_url('/auth/register') ?>">Don’t have an account? Register here</a> <br>
                            <a href="<?= base_url('/auth/forgot') ?>" class="btn btn-warning mt-3 btn-sm">Lupa Password?</a> <br>
                            <a href="<?= base_url('/auth/google') ?>" class="btn btn-danger mt-3 btn-sm"><svg class="icon icon-tabler"
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-brand-google">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M20.945 11a9 9 0 1 1 -3.284 -5.997l-2.655 2.392a5.5 5.5 0 1 0 2.119 6.605h-4.125v-3h7.945z" />
                                </svg> Login dengan Google</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>

</html>