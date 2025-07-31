<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Produk A - Baju Keren',
                'description' => 'Baju katun berkualitas tinggi, nyaman dipakai sehari-hari.',
                'price'    => 150000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Produk B - Celana Jeans',
                'description' => 'Celana jeans model terbaru, membuat Anda tampil lebih gaya.',
                'price'    => 250000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Produk C - Sepatu Lari',
                'description' => 'Sepatu lari ringan dan empuk, cocok untuk olahraga.',
                'price'    => 350000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Using Query Builder
        $this->db->table('products')->insertBatch($data);
    }
}
