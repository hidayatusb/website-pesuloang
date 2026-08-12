<?php

namespace Database\Seeders;

use App\Models\VillageInfographic;
use Illuminate\Database\Seeder;

class VillageInfographicSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'title' => 'Data Kependudukan Desa 2024',
                'slug' => 'data-kependudukan-desa-2024',
                'description' => 'Ringkasan jumlah penduduk, kepala keluarga, dan sebaran per dusun tahun 2024.',
                'image_path' => 'assets/media/images/600x600/1.jpg',
                'published_at' => '2024-06-10 09:00:00',
                'sort_order' => 1,
            ],
            [
                'title' => 'Alur Pengurusan Surat Keterangan',
                'slug' => 'alur-pengurusan-surat-keterangan',
                'description' => 'Langkah-langkah dan persyaratan mengurus surat keterangan di kantor desa.',
                'image_path' => 'assets/media/images/600x600/2.jpg',
                'published_at' => '2024-06-05 10:00:00',
                'sort_order' => 2,
            ],
            [
                'title' => 'APBDes 2024: Rencana dan Realisasi',
                'slug' => 'apbdes-2024-rencana-dan-realisasi',
                'description' => 'Transparansi anggaran pendapatan dan belanja desa tahun anggaran 2024.',
                'image_path' => 'assets/media/images/600x600/3.jpg',
                'published_at' => '2024-05-28 08:30:00',
                'sort_order' => 3,
            ],
            [
                'title' => 'Jadwal Posyandu dan Layanan Kesehatan',
                'slug' => 'jadwal-posyandu-dan-layanan-kesehatan',
                'description' => 'Jadwal rutin posyandu balita, lansia, dan pemeriksaan kesehatan gratis.',
                'image_path' => 'assets/media/images/600x600/4.jpg',
                'published_at' => '2024-05-20 14:00:00',
                'sort_order' => 4,
            ],
            [
                'title' => 'Potensi Pertanian Desa',
                'slug' => 'potensi-pertanian-desa',
                'description' => 'Komoditas unggulan dan luas lahan pertanian produktif desa.',
                'image_path' => 'assets/media/images/600x600/5.jpg',
                'published_at' => '2024-05-12 09:30:00',
                'sort_order' => 5,
            ],
            [
                'title' => 'Tahapan Musyawarah Desa 2024',
                'slug' => 'tahapan-musyawarah-desa-2024',
                'description' => 'Timeline pelaksanaan musyawarah perencanaan pembangunan desa.',
                'image_path' => 'assets/media/images/600x600/6.jpg',
                'published_at' => '2024-05-02 10:00:00',
                'sort_order' => 6,
            ],
        ];

        foreach ($items as $item) {
            VillageInfographic::query()->updateOrCreate(
                ['slug' => $item['slug']],
                [
                    ...$item,
                    'is_published' => true,
                ]
            );
        }
    }
}
