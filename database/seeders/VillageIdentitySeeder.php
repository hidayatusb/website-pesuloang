<?php

namespace Database\Seeders;

use App\Models\VillageIdentity;
use Illuminate\Database\Seeder;

class VillageIdentitySeeder extends Seeder
{
    public function run(): void
    {
        VillageIdentity::query()->create([
            'name' => 'Desa Sukamaju',
            'kecamatan' => 'Kec. Cikarang Utara',
            'kabupaten' => 'Kab. Bekasi',
            'welcome_text' => 'Selamat Datang di',
            'tagline' => 'Desa yang maju, mandiri, dan sejahtera berlandaskan gotong royong dan kebersamaan.',
            'hero_image_path' => 'assets/media/images/2600x1600/bg-3.png',
            'population' => '2.350 Jiwa',
            'households' => '812 KK',
            'area' => '3,25 Km²',
            'hamlets' => '4 Dusun',
            'about_label' => 'Tentang Kami',
            'about_title' => 'Membangun Desa, Mewujudkan Masa Depan',
            'about_description' => 'Desa Sukamaju berkomitmen untuk meningkatkan kualitas hidup warga melalui pembangunan infrastruktur, pelayanan publik yang transparan, serta pemberdayaan masyarakat. Kami percaya bahwa kemajuan desa dimulai dari partisipasi aktif seluruh warga.',
            'about_image_path' => 'assets/media/images/2600x1200/2.png',
            'address' => 'Jl. Raya Sukamaju No. 1, Cikarang Utara, Bekasi, Jawa Barat 17530',
            'phone' => '(021) 1234-5678',
            'email' => 'info@desasukamaju.go.id',
            'facebook_url' => '#',
            'instagram_url' => '#',
            'youtube_url' => '#',
        ]);
    }
}
