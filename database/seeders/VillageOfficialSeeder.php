<?php

namespace Database\Seeders;

use App\Models\VillageOfficial;
use Illuminate\Database\Seeder;

class VillageOfficialSeeder extends Seeder
{
    public function run(): void
    {
        $officials = [
            ['name' => 'H. Ahmad Suryadi, S.Sos.', 'position' => 'Kepala Desa', 'photo_path' => 'assets/media/avatars/300-1.png', 'sort_order' => 1],
            ['name' => 'Dedi Kurniawan, S.E.', 'position' => 'Sekretaris Desa', 'photo_path' => 'assets/media/avatars/300-2.png', 'sort_order' => 2],
            ['name' => 'Siti Rahmawati', 'position' => 'Kaur Keuangan', 'photo_path' => 'assets/media/avatars/300-10.png', 'sort_order' => 3],
            ['name' => 'Budi Santoso', 'position' => 'Kaur Perencanaan', 'photo_path' => 'assets/media/avatars/300-11.png', 'sort_order' => 4],
            ['name' => 'Rina Marlina', 'position' => 'Kaur Tata Usaha & Umum', 'photo_path' => 'assets/media/avatars/300-12.png', 'sort_order' => 5],
            ['name' => 'Joko Prasetyo', 'position' => 'Kasi Pemerintahan', 'photo_path' => 'assets/media/avatars/300-13.png', 'sort_order' => 6],
            ['name' => 'Andi Wijaya', 'position' => 'Kasi Kesejahteraan', 'photo_path' => 'assets/media/avatars/300-14.png', 'sort_order' => 7],
            ['name' => 'Lestari Handayani', 'position' => 'Kasi Pelayanan', 'photo_path' => 'assets/media/avatars/300-15.png', 'sort_order' => 8],
        ];

        foreach ($officials as $official) {
            VillageOfficial::query()->updateOrCreate(
                ['name' => $official['name']],
                [
                    ...$official,
                    'is_active' => true,
                ]
            );
        }
    }
}
