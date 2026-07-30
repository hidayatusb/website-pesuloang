<?php

namespace Database\Seeders;

use App\Models\VillagePost;
use Illuminate\Database\Seeder;

class VillagePostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title' => 'Pembangunan Jalan Desa Sukamaju Tahap 2',
                'slug' => 'pembangunan-jalan-desa-sukamaju-tahap-2',
                'type' => 'berita',
                'category' => 'Pembangunan',
                'excerpt' => 'Pembangunan jalan desa tahap kedua dimulai untuk meningkatkan aksesibilitas warga.',
                'content' => '<p>Pemerintah Desa Sukamaju memulai pembangunan jalan desa tahap kedua yang mencakup perbaikan jalan utama dan jalan lingkungan di beberapa dusun.</p><p>Kegiatan ini diharapkan dapat meningkatkan aksesibilitas warga menuju fasilitas umum dan pusat pelayanan desa.</p>',
                'image_path' => 'assets/media/images/2600x1200/1.png',
                'is_published' => true,
                'published_at' => '2024-05-20 08:00:00',
                'author_name' => 'Humas Desa Sukamaju',
            ],
            [
                'title' => 'Festival Budaya Desa Sukamaju 2024',
                'slug' => 'festival-budaya-desa-sukamaju-2024',
                'type' => 'berita',
                'category' => 'Kegiatan',
                'excerpt' => 'Festival budaya tahunan desa kembali digelar dengan berbagai pertunjukan seni lokal.',
                'content' => '<p>Festival Budaya Desa Sukamaju 2024 menghadirkan pertunjukan tari tradisional, pameran UMKM, dan berbagai lomba antarwarga.</p><p>Acara ini menjadi wadah pelestarian budaya dan penguatan kebersamaan masyarakat desa.</p>',
                'image_path' => 'assets/media/images/2600x1200/2.png',
                'is_published' => true,
                'published_at' => '2024-05-15 09:00:00',
                'author_name' => 'Humas Desa Sukamaju',
            ],
            [
                'title' => 'Jadwal Pelayanan Administrasi Bulan Juni',
                'slug' => 'jadwal-pelayanan-administrasi-bulan-juni',
                'type' => 'pengumuman',
                'category' => 'Pengumuman',
                'excerpt' => 'Informasi jadwal pelayanan administrasi desa pada bulan Juni.',
                'content' => '<p>Berikut jadwal pelayanan administrasi Desa Sukamaju bulan Juni:</p><ul><li>Senin–Jumat: 08.00–15.00 WIB</li><li>Sabtu: 08.00–12.00 WIB</li></ul><p>Mohon membawa KTP dan dokumen pendukung saat mengurus administrasi.</p>',
                'image_path' => 'assets/media/images/2600x1200/3.png',
                'is_published' => true,
                'published_at' => '2024-05-10 07:30:00',
                'author_name' => 'Sekretariat Desa',
            ],
        ];

        foreach ($posts as $post) {
            VillagePost::query()->create($post);
        }
    }
}
