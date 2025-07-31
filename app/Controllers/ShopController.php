<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ProductModel;
use App\Models\TransactionModel;

class ShopController extends BaseController
{
    public function index()
    {
        // Pastikan user sudah login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Silakan login terlebih dahulu untuk berbelanja.');
        }

        $productModel = new ProductModel();
        
        $data = [
            'title' => 'Toko Sederhana',
            'products' => $productModel->findAll(),
            'clientKey' => getenv('MIDTRANS_CLIENT_KEY') // Ambil client key dari .env
        ];

        return view('shop/index', $data);
    }

    public function history()
    {
        $transactionModel = new TransactionModel();
        $userId = session()->get('userId');

        $data = [
            'title' => 'Riwayat Transaksi',
            'transactions' => $transactionModel->getHistoryByUser($userId),
            'clientKey' => getenv('MIDTRANS_CLIENT_KEY')
        ];

        return view('shop/history', $data);
    }
}
