<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table            = 'transactions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'order_id', 
        'user_id', 
        'product_id', 
        'gross_amount', 
        'payment_type', 
        'transaction_time', 
        'transaction_status', 
        'snap_token'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

        /**
     * Mengambil riwayat transaksi berdasarkan ID user
     * @param int $userId
     * @return array
     */
    public function getHistoryByUser(int $userId): array
    {
        return $this->select('transactions.*, products.name as product_name')
                    ->join('products', 'products.id = transactions.product_id')
                    ->where('transactions.user_id', $userId)
                    ->orderBy('transactions.created_at', 'DESC')
                    ->findAll();
    }
}
