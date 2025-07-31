<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TransactionModel;
use App\Models\ProductModel;
use App\Models\UserModel; // Pastikan Anda memiliki UserModel

class MidtransController extends BaseController
{
    public function __construct()
    {
        // Set konfigurasi Midtrans
        \Midtrans\Config::$serverKey = getenv('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    }

    public function process()
    {
        // Pastikan ini adalah request AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403, 'Forbidden');
        }

        // Ambil data dari request POST
        $json = $this->request->getJSON();
        $productId = $json->product_id;
        $price = $json->price;
        $productName = $json->product_name;
        
        $userId = session()->get('userId');

        // Buat order ID yang unik
        $orderId = 'ORDER-' . $productId . '-' . time();

        // Siapkan parameter untuk Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int)$price,
            ],
            'item_details' => [
                [
                    'id' => $productId,
                    'price' => (int)$price,
                    'quantity' => 1,
                    'name' => $productName,
                ]
            ],
            'customer_details' => [
                'first_name' => session()->get('username'), // Asumsi nama depan adalah username
                'last_name' => '', // Bisa dikosongkan atau diisi data lain
                'email' => session()->get('email'),
                'phone' => '081234567890', // Ganti dengan nomor telepon user jika ada
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            
            // Simpan transaksi ke database dengan status 'pending'
            $transactionModel = new TransactionModel();
            $transactionModel->save([
                'order_id' => $orderId,
                'user_id' => $userId,
                'product_id' => $productId,
                'gross_amount' => (int)$price,
                'transaction_status' => 'pending',
                'snap_token' => $snapToken,
            ]);

            return $this->response->setJSON(['snap_token' => $snapToken]);

        } catch (\Exception $e) {
            log_message('error', 'Midtrans Snap Token Error: ' . $e->getMessage());
            return $this->response->setJSON(['error' => $e->getMessage()])->setStatusCode(500);
        }
    }

    public function notification()
    {
        // Terima notifikasi dari Midtrans
        $notification = new \Midtrans\Notification();
        
        $orderId = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status;
        $paymentType = $notification->payment_type;
        $transactionTime = $notification->transaction_time;

        log_message('info', "Midtrans Notification Received. Order ID: {$orderId}, Status: {$transactionStatus}");

        // Verifikasi signature key
        $signatureKey = hash('sha512', $orderId . $notification->status_code . $notification->gross_amount . getenv('MIDTRANS_SERVER_KEY'));
        if ($signatureKey != $notification->signature_key) {
            log_message('error', "Midtrans notification signature mismatch. Order ID: {$orderId}");
            return $this->response->setStatusCode(403, 'Forbidden');
        }

        $transactionModel = new TransactionModel();
        $transaction = $transactionModel->where('order_id', $orderId)->first();

        if (!$transaction) {
            log_message('error', "Transaction not found for Order ID: {$orderId}");
            return $this->response->setStatusCode(404, 'Not Found');
        }

        // Update status transaksi berdasarkan notifikasi
        $updateData = [
            'transaction_status' => $transactionStatus,
            'payment_type' => $paymentType,
            'transaction_time' => $transactionTime
        ];

        // Logika status
        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'accept') {
                // Transaksi berhasil dan aman
                $transactionModel->update($transaction['id'], $updateData);
            }
        } else if ($transactionStatus == 'settlement') {
            // Transaksi berhasil diselesaikan
            $transactionModel->update($transaction['id'], $updateData);
        } else if ($transactionStatus == 'deny') {
            // Transaksi ditolak
            $transactionModel->update($transaction['id'], $updateData);
        } else if ($transactionStatus == 'cancel' || $transactionStatus == 'expire') {
            // Transaksi dibatalkan atau kedaluwarsa
            $transactionModel->update($transaction['id'], $updateData);
        } else if ($transactionStatus == 'pending') {
            // Transaksi masih menunggu pembayaran
            $transactionModel->update($transaction['id'], $updateData);
        }

        return $this->response->setStatusCode(200, 'OK');
    }
}