<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    \App\Models\Item::create([
        'kode' => 'PK001',
        'gambar' => 'https://letsenhance.io/static/8f5e523ee6b2479e26ecc91b9c25261e/1015f/MainAfter.jpg',
        'ket' => 'Keterangan item pertama'
    ]);

    \App\Models\Item::create([
        'kode' => 'PK002',
        'gambar' => 'https://dfstudio-d420.kxcdn.com/wordpress/wp-content/uploads/2019/06/digital_camera_photo-1080x675.jpg',
        'ket' => 'Keterangan item kedua'
    ]);
}

}
