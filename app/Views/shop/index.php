<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Midtrans Snap.js -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= esc($clientKey) ?>"></script>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center ">
            <h1>Selamat Datang di Toko Kami, <?= session()->get('username') ?>!</h1>
            <div>
                <a href="/auth/logout" class="btn btn-danger">Logout</a>
            </div>
        </div>
        <a href="/shop/history" class="btn btn-info mb-4">Riwayat Transaksi</a>
        
        <div id="notification" class="alert d-none" role="alert"></div>

        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= esc($product['name']) ?></h5>
                            <p class="card-text"><?= esc($product['description']) ?></p>
                            <p class="card-text"><strong>Harga: Rp <?= number_format($product['price'], 0, ',', '.') ?></strong></p>
                            <button class="btn btn-primary buy-button" 
                                    data-id="<?= esc($product['id']) ?>"
                                    data-price="<?= esc($product['price']) ?>"
                                    data-name="<?= esc($product['name']) ?>">
                                Beli Sekarang
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            const buyButtons = document.querySelectorAll('.buy-button');
            buyButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    
                    const productId = this.dataset.id;
                    const price = this.dataset.price;
                    const productName = this.dataset.name;
                    const notification = document.getElementById('notification');

                    // Tampilkan notifikasi loading
                    showNotification('Memproses pembayaran...', 'info');

                    // Kirim request ke backend untuk mendapatkan Snap Token
                    fetch('/midtrans/process', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            price: price,
                            product_name: productName
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.snap_token) {
                            // Sembunyikan notifikasi loading
                            notification.classList.add('d-none');
                            
                            // Buka popup pembayaran Midtrans
                            snap.pay(data.snap_token, {
                                onSuccess: function(result){
                                    /* Anda bisa menambahkan logika di sini, misalnya redirect ke halaman sukses */
                                    console.log(result);
                                    showNotification('Pembayaran Berhasil! Status: ' + result.transaction_status, 'success');
                                },
                                onPending: function(result){
                                    /* Anda bisa menambahkan logika di sini, misalnya redirect ke halaman pending */
                                    console.log(result);
                                    showNotification('Pembayaran Tertunda. Status: ' + result.transaction_status, 'warning');
                                },
                                onError: function(result){
                                    /* Anda bisa menambahkan logika di sini, misalnya redirect ke halaman error */
                                    console.log(result);
                                    showNotification('Pembayaran Gagal. Status: ' + result.status_message, 'danger');
                                },
                                onClose: function(){
                                    /* Dijalankan saat customer menutup popup pembayaran tanpa menyelesaikan proses */
                                    showNotification('Anda menutup popup pembayaran.', 'info');
                                }
                            });
                        } else {
                            showNotification('Gagal mendapatkan token pembayaran. ' + (data.error || ''), 'danger');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Terjadi kesalahan. Silakan coba lagi.', 'danger');
                    });
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
