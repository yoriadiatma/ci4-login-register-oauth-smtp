<!DOCTYPE html>
<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
</head>

<body class="p-5">
    <div class="container">
        <h3>Reset Password</h3>
        <?php if (session('error')): ?>
            <div class="alert alert-danger"><?= session('error') ?></div>
        <?php endif; ?>
        <?php if (session('success')): ?>
            <div class="alert alert-success"><?= session('success') ?></div>
        <?php endif; ?>

        <form action="<?= base_url('/auth/reset/' . $token) ?>" method="POST">
            <?= csrf_field() ?>
            <input type="password" name="new_pass" placeholder="New password" required class="form-control mb-2">
            <input type="password" name="conf_pass" placeholder="Confirm password" required class="form-control mb-2">
            <button type="submit" class="btn btn-primary">Reset</button>
        </form>
    </div>
</body>

</html>