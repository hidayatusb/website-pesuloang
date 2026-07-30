<?php

namespace Database\Seeders;

use App\Models\VillageUmkm;
use Illuminate\Database\Seeder;

class VillageUmkmSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'name' => 'Warung Makan Bu Siti',
                'slug' => 'warung-makan-bu-siti',
                'category' => 'makanan',
                'owner_name' => 'Siti Aminah',
                'excerpt' => 'Masakan rumahan khas desa dengan bahan baku lokal segar.',
                'description' => '<p>Warung Makan Bu Siti menyajikan aneka masakan rumahan seperti nasi liwet, ayam bakar, dan sayur lodeh dengan cita rasa khas pedesaan.</p><p>Buka setiap hari pukul 07.00–20.00 WIB di Dusun Sukamaju.</p>',
                'image_path' => 'assets/media/images/2600x1200/1.png',
                'phone' => '0812-3456-7890',
                'whatsapp' => '081234567890',
                'address' => 'Dusun Sukamaju, RT 02/RW 01',
                'products' => 'Nasi liwet, ayam bakar, sayur lodeh, es teh manis',
                'is_published' => true,
                'is_featured' => true,
                'published_at' => '2024-05-18 08:00:00',
                'sort_order' => 1,
            ],
            [
                'name' => 'Kerajinan Anyaman Bambu Pak Joko',
                'slug' => 'kerajinan-anyaman-bambu-pak-joko',
                'category' => 'kerajinan',
                'owner_name' => 'Joko Santoso',
                'excerpt' => 'Produk anyaman bambu handmade untuk kebutuhan rumah tangga dan souvenir.',
                'description' => '<p>Usaha kerajinan anyaman bambu yang memproduksi tikar, keranjang, dan hiasan dinding dengan teknik tradisional turun-temurun.</p><p>Menerima pesanan custom untuk acara dan souvenir desa.</p>',
                'image_path' => 'assets/media/images/2600x1200/2.png',
                'phone' => '0813-9876-5432',
                'whatsapp' => '081398765432',
                'address' => 'Dusun Mekar Sari, RT 01/RW 02',
                'products' => 'Tikar bambu, keranjang, vas anyaman, souvenir custom',
                'is_published' => true,
                'is_featured' => true,
                'published_at' => '2024-05-16 09:00:00',
                'sort_order' => 2,
            ],
            [
                'name' => 'Bengkel Motor Mandiri',
                'slug' => 'bengkel-motor-mandiri',
                'category' => 'jasa',
                'owner_name' => 'Ahmad Rizki',
                'excerpt' => 'Layanan servis dan perawatan motor untuk warga desa.',
                'description' => '<p>Bengkel Motor Mandiri menyediakan servis rutin, ganti oli, tune-up, dan perbaikan ringan sepeda motor.</p><p>Pelayanan cepat dengan harga terjangkau.</p>',
                'image_path' => 'assets/media/images/2600x1200/3.png',
                'phone' => '0857-1122-3344',
                'whatsapp' => '085711223344',
                'address' => 'Jl. Raya Desa No. 12',
                'products' => 'Servis motor, ganti oli, tune-up, perbaikan ringan',
                'is_published' => true,
                'is_featured' => false,
                'published_at' => '2024-05-14 10:00:00',
                'sort_order' => 3,
            ],
            [
                'name' => 'Kebun Organik Pak Budi',
                'slug' => 'kebun-organik-pak-budi',
                'category' => 'pertanian',
                'owner_name' => 'Budi Hartono',
                'excerpt' => 'Sayuran dan buah organik hasil kebun lokal tanpa pestisida kimia.',
                'description' => '<p>Kebun Organik Pak Budi memasok sayuran segar seperti kangkung, bayam, tomat, dan cabai untuk warga desa.</p><p>Tersedia juga paket sembako sayur mingguan.</p>',
                'image_path' => 'assets/media/images/2600x1200/1.png',
                'phone' => '0821-5566-7788',
                'whatsapp' => '082155667788',
                'address' => 'Dusun Harapan, RT 03/RW 01',
                'products' => 'Sayur organik, buah lokal, paket sembako sayur',
                'is_published' => true,
                'is_featured' => true,
                'published_at' => '2024-05-12 07:30:00',
                'sort_order' => 4,
            ],
        ];

        foreach ($items as $item) {
            VillageUmkm::query()->updateOrCreate(
                ['slug' => $item['slug']],
                $item
            );
        }
    }
}
