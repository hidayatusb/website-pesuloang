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
            [
                'name' => 'Pertumbuhan Penduduk',
                'slug' => 'pertumbuhan-penduduk',
                'icon' => 'ki-chart-line-up',
                'description' => 'Tren jumlah penduduk desa per tahun (2019-2024).',
                'sort_order' => 4,
                'columns' => [
                    ['key' => 'tahun', 'label' => 'Tahun', 'type' => 'text', 'required' => true],
                    ['key' => 'jumlah', 'label' => 'Jumlah Penduduk', 'type' => 'number', 'required' => true],
                    ['key' => 'satuan', 'label' => 'Satuan', 'type' => 'text', 'required' => false],
                ],
                'chart_label_key' => 'tahun',
                'chart_value_key' => 'jumlah',
                'items' => [
                    ['tahun' => '2019', 'jumlah' => '2.105', 'satuan' => 'Jiwa'],
                    ['tahun' => '2020', 'jumlah' => '2.148', 'satuan' => 'Jiwa'],
                    ['tahun' => '2021', 'jumlah' => '2.196', 'satuan' => 'Jiwa'],
                    ['tahun' => '2022', 'jumlah' => '2.241', 'satuan' => 'Jiwa'],
                    ['tahun' => '2023', 'jumlah' => '2.298', 'satuan' => 'Jiwa'],
                    ['tahun' => '2024', 'jumlah' => '2.350', 'satuan' => 'Jiwa'],
                ],
            ],
            [
                'name' => 'Penduduk per Dusun',
                'slug' => 'penduduk-per-dusun',
                'icon' => 'ki-home-3',
                'description' => 'Perbandingan jumlah penduduk tiap dusun selama tiga tahun terakhir.',
                'sort_order' => 5,
                'columns' => [
                    ['key' => 'dusun', 'label' => 'Dusun', 'type' => 'text', 'required' => true],
                    ['key' => 'tahun_2022', 'label' => '2022', 'type' => 'number', 'required' => true],
                    ['key' => 'tahun_2023', 'label' => '2023', 'type' => 'number', 'required' => true],
                    ['key' => 'tahun_2024', 'label' => '2024', 'type' => 'number', 'required' => true],
                ],
                'chart_label_key' => 'dusun',
                'chart_value_key' => 'tahun_2024',
                'items' => [
                    ['dusun' => 'Dusun Mawar', 'tahun_2022' => '598', 'tahun_2023' => '612', 'tahun_2024' => '625'],
                    ['dusun' => 'Dusun Melati', 'tahun_2022' => '544', 'tahun_2023' => '561', 'tahun_2024' => '574'],
                    ['dusun' => 'Dusun Kenanga', 'tahun_2022' => '571', 'tahun_2023' => '580', 'tahun_2024' => '592'],
                    ['dusun' => 'Dusun Anggrek', 'tahun_2022' => '528', 'tahun_2023' => '545', 'tahun_2024' => '559'],
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

            $labelKey = $category->chart_label_key;

            foreach ($items as $itemIndex => $itemData) {
                $category->items()->updateOrCreate(
                    ['data->'.$labelKey => $itemData[$labelKey]],
                    [
                        'data' => $itemData,
                        'sort_order' => $itemIndex + 1,
                        'is_active' => true,
                    ]
                );
            }
        }

        VillageStatisticCategory::clearCache();
    }
}
