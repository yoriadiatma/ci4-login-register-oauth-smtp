<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card p-4 shadow">
                    <h4 class="mb-4 text-center">Register</h4>
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
                    <form action="<?= base_url('/auth/register') ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input 
                                type="text" 
                                name="username" 
                                id="username" 
                                class="form-control" 
                                placeholder="Enter your username" 
                                required>
                        </div>

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
                                placeholder="Enter password" 
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="confirm" class="form-label">Confirm Password</label>
                            <input 
                                type="password" 
                                name="confirm" 
                                id="confirm" 
                                class="form-control" 
                                placeholder="Confirm password" 
                                required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Register
                        </button>

                        <div class="text-center mt-3">
                            <a href="<?= base_url('/auth/login') ?>">Already have an account? Login here</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>
