<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class VillageIdentity extends Model
{
    protected $fillable = [
        'name',
        'kecamatan',
        'kabupaten',
        'logo_path',
        'welcome_text',
        'tagline',
        'hero_image_path',
        'population',
        'households',
        'area',
        'hamlets',
        'about_label',
        'about_title',
        'about_description',
        'about_image_path',
        'address',
        'phone',
        'email',
        'facebook_url',
        'instagram_url',
        'youtube_url',
    ];

    public static function current(): self
    {
        return Cache::remember('village_identity', 3600, function () {
            return static::query()->first() ?? static::make([
                'name' => 'Desa Sukamaju',
                'kecamatan' => 'Kec. Cikarang Utara',
                'kabupaten' => 'Kab. Bekasi',
                'welcome_text' => 'Selamat Datang di',
                'tagline' => 'Desa yang maju, mandiri, dan sejahtera berlandaskan gotong royong dan kebersamaan.',
                'population' => '2.350 Jiwa',
                'households' => '812 KK',
                'area' => '3,25 Km²',
                'hamlets' => '4 Dusun',
                'about_label' => 'Tentang Kami',
                'about_title' => 'Membangun Desa, Mewujudkan Masa Depan',
                'about_description' => 'Desa Sukamaju berkomitmen untuk meningkatkan kualitas hidup warga.',
                'address' => 'Jl. Raya Sukamaju No. 1, Bekasi',
            ]);
        });
    }

    public static function clearCache(): void
    {
        Cache::forget('village_identity');
    }

    public function locationLabel(): string
    {
        return trim("{$this->kecamatan}, {$this->kabupaten}");
    }

    public function imageUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (str_starts_with($path, 'assets/')) {
            return asset($path);
        }

        // Path relatif agar logo tampil di host/port manapun (mis. 127.0.0.1:8000)
        return '/storage/'.ltrim($path, '/');
    }

    public function logoUrl(): ?string
    {
        return $this->imageUrl($this->logo_path);
    }

    public function heroImageUrl(): string
    {
        return $this->imageUrl($this->hero_image_path)
            ?? asset('assets/media/images/2600x1600/bg-3.png');
    }

    public function aboutImageUrl(): string
    {
        return $this->imageUrl($this->about_image_path)
            ?? asset('assets/media/images/2600x1200/2.png');
    }
}
