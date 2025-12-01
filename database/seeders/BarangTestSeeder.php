<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Sales;

class BarangTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek jika sudah ada data
        if (Barang::count() > 0) {
            echo "Barang sudah ada di database\n";
            return;
        }

        // Buat kategori test jika tidak ada
        $kategori = Kategori::firstOrCreate(
            ['nama' => 'Test'],
            ['nama' => 'Test']
        );

        // Buat sales test jika tidak ada
        $sales = Sales::firstOrCreate(
            ['nama' => 'Test Sales'],
            ['nama' => 'Test Sales']
        );

        // Buat barang test
        Barang::create([
            'kode_barang' => 'TEST001',
            'nama' => 'Test Item 1',
            'stok' => 100,
            'harga_beli' => 10000,
            'harga_jual' => 15000,
            'id_kategori' => $kategori->id,
            'id_sales' => $sales->id,
        ]);

        Barang::create([
            'kode_barang' => 'TEST002',
            'nama' => 'Test Item 2',
            'stok' => 50,
            'harga_beli' => 20000,
            'harga_jual' => 30000,
            'id_kategori' => $kategori->id,
            'id_sales' => $sales->id,
        ]);

        echo "Barang test seeder completed\n";
    }
}
