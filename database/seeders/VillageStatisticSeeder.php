<?php

namespace Database\Seeders;

use App\Models\VillageStatisticCategory;
use Illuminate\Database\Seeder;

class VillageStatisticSeeder extends Seeder
{
    public function run(): void
    {
        $defaultColumns = VillageStatisticCategory::defaultColumns();

        $categories = [
            [
                'name' => 'Ringkasan Desa',
                'slug' => 'ringkasan-desa',
                'icon' => 'ki-chart-line',
                'description' => 'Indikator utama desa yang ditampilkan di halaman beranda.',
                'sort_order' => 1,
                'show_on_home' => true,
                'columns' => $defaultColumns,
                'chart_label_key' => 'indikator',
                'chart_value_key' => 'nilai',
                'items' => [
                    ['indikator' => 'Jumlah Penduduk', 'nilai' => '2.350', 'satuan' => 'Jiwa', 'periode' => '2024'],
                    ['indikator' => 'Kepala Keluarga', 'nilai' => '812', 'satuan' => 'KK', 'periode' => '2024'],
                    ['indikator' => 'Luas Wilayah', 'nilai' => '3,25', 'satuan' => 'Km²', 'periode' => '2024'],
                    ['indikator' => 'Jumlah Dusun', 'nilai' => '4', 'satuan' => 'Dusun', 'periode' => '2024'],
                ],
            ],
            [
                'name' => 'Penduduk',
                'slug' => 'penduduk',
                'icon' => 'ki-people',
                'description' => 'Data kependudukan berdasarkan jenis kelamin, usia, dan pendidikan.',
                'sort_order' => 2,
                'columns' => $defaultColumns,
                'chart_label_key' => 'indikator',
                'chart_value_key' => 'nilai',
                'items' => [
                    ['indikator' => 'Laki-laki', 'nilai' => '1.180', 'satuan' => 'Jiwa', 'periode' => '2024'],
                    ['indikator' => 'Perempuan', 'nilai' => '1.170', 'satuan' => 'Jiwa', 'periode' => '2024'],
                    ['indikator' => 'Usia Produktif (15-64)', 'nilai' => '1.420', 'satuan' => 'Jiwa', 'periode' => '2024'],
                ],
            ],
            [
                'name' => 'Pendidikan',
                'slug' => 'pendidikan',
                'icon' => 'ki-book',
                'description' => 'Capaian pendidikan dan fasilitas pendidikan di desa.',
                'sort_order' => 3,
                'columns' => $defaultColumns,
                'chart_label_key' => 'indikator',
                'chart_value_key' => 'nilai',
                'items' => [
                    ['indikator' => 'PAUD/TK', 'nilai' => '2', 'satuan' => 'Unit', 'periode' => '2024'],
                    ['indikator' => 'SD/MI', 'nilai' => '1', 'satuan' => 'Unit', 'periode' => '2024'],
                    ['indikator' => 'SMP/MTs', 'nilai' => '1', 'satuan' => 'Unit', 'periode' => '2024'],
                ],
            ],
        ];

        foreach ($categories as $categoryData) {
            $items = $categoryData['items'];
            unset($categoryData['items']);

            $category = VillageStatisticCategory::query()->updateOrCreate(
                ['slug' => $categoryData['slug']],
                [
                    ...$categoryData,
                    'is_active' => true,
                ]
            );

            foreach ($items as $itemIndex => $itemData) {
                $category->items()->updateOrCreate(
                    ['data->indikator' => $itemData['indikator']],
                    [
                        'data' => $itemData,
                        'sort_order' => $itemIndex + 1,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
