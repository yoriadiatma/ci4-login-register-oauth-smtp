<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Midtrans Snap.js - Diperlukan untuk tombol bayar -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= esc($clientKey) ?>"></script>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><?= esc($title) ?></h1>
            <div>
                <a href="/shop" class="btn btn-primary">Kembali ke Toko</a>
                <a href="/auth/logout" class="btn btn-danger">Logout</a>
            </div>
        </div>
        
        <div id="notification" class="alert d-none" role="alert"></div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Order ID</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($transactions)): ?>
                        <?php foreach ($transactions as $trx): ?>
                            <tr>
                                <td><?= esc($trx['order_id']) ?></td>
                                <td><?= esc($trx['product_name']) ?></td>
                                <td>Rp <?= number_format($trx['gross_amount'], 0, ',', '.') ?></td>
                                <td>
                                    <?php
                                        $status = $trx['transaction_status'];
                                        $badgeClass = 'bg-secondary'; // Default
                                        if ($status == 'settlement' || $status == 'capture') {
                                            $badgeClass = 'bg-success';
                                        } elseif ($status == 'pending') {
                                            $badgeClass = 'bg-warning text-dark';
                                        } elseif ($status == 'expire' || $status == 'cancel' || $status == 'deny') {
                                            $badgeClass = 'bg-danger';
                                        }
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= esc(ucfirst($status)) ?></span>
                                </td>
                                <td>
                                    <!-- Logika untuk menampilkan tombol bayar -->
                                    <?php if ($status == 'pending'): ?>
                                        <button class="btn btn-sm btn-success pay-now-button" data-token="<?= esc($trx['snap_token']) ?>">
                                            Bayar Sekarang
                                        </button>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">Anda belum memiliki riwayat transaksi.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Skrip untuk menangani tombol "Bayar Sekarang" -->
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            const payButtons = document.querySelectorAll('.pay-now-button');
            payButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    
                    const snapToken = this.dataset.token;
                    
                    if (snapToken) {
                        snap.pay(snapToken, {
                            onSuccess: function(result){
                                console.log(result);
                                showNotification('Pembayaran Berhasil! Halaman akan dimuat ulang.', 'success');
                                setTimeout(() => window.location.reload(), 3000);
                            },
                            onPending: function(result){
                                console.log(result);
                                showNotification('Pembayaran Tertunda. Status: ' + result.transaction_status, 'warning');
                            },
                            onError: function(result){
                                console.log(result);
                                showNotification('Pembayaran Gagal. Status: ' + result.status_message, 'danger');
                            },
                            onClose: function(){
                                showNotification('Anda menutup popup pembayaran.', 'info');
                            }
                        });
                    } else {
                        showNotification('Snap Token tidak ditemukan.', 'danger');
                    }
                });
            });

            function showNotification(message, type) {
                const notification = document.getElementById('notification');
                notification.className = 'alert alert-' + type;
                notification.textContent = message;
                notification.classList.remove('d-none');
            }
        });
    </script>
</body>
</html>
