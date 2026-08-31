<?php

namespace App\Livewire\Dashboard;

use App\Models\VillageDocument;
use App\Models\VillageGallery;
use App\Models\VillageInfographic;
use App\Models\VillagePost;
use App\Models\VillageService;
use App\Models\VillageStatisticItem;
use App\Models\VillageUmkm;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.demo1.base')]
class Index extends Component
{
    public function render()
    {
        $stats = [
            [
                'label' => 'Berita & Pengumuman',
                'icon' => 'ki-book',
                'route' => route('desa.berita.index'),
                'total' => VillagePost::query()->count('*'),
                'published' => VillagePost::query()->where('is_published', true)->count('*'),
            ],
            [
                'label' => 'UMKM Desa',
                'icon' => 'ki-shop',
                'route' => route('desa.umkm.index'),
                'total' => VillageUmkm::query()->count('*'),
                'published' => VillageUmkm::query()->where('is_published', true)->count('*'),
            ],
            [
                'label' => 'Layanan Desa',
                'icon' => 'ki-clipboard',
                'route' => route('desa.layanan.index'),
                'total' => VillageService::query()->count('*'),
                'published' => VillageService::query()->where('is_published', true)->count('*'),
            ],
            [
                'label' => 'Infografis',
                'icon' => 'ki-picture',
                'route' => route('desa.infografis.index'),
                'total' => VillageInfographic::query()->count('*'),
                'published' => VillageInfographic::query()->where('is_published', true)->count('*'),
            ],
            [
                'label' => 'Dokumen Desa',
                'icon' => 'ki-file',
                'route' => route('desa.dokumen.index'),
                'total' => VillageDocument::query()->count('*'),
                'published' => VillageDocument::query()->where('is_published', true)->count('*'),
            ],
            [
                'label' => 'Dokumentasi / Galeri',
                'icon' => 'ki-camera',
                'route' => route('desa.galeri.index'),
                'total' => VillageGallery::query()->count('*'),
                'published' => VillageGallery::query()->where('is_published', true)->count('*'),
            ],
            [
                'label' => 'Data Statistik',
                'icon' => 'ki-chart-line',
                'route' => route('desa.statistika.index'),
                'total' => VillageStatisticItem::query()->count('*'),
                'published' => VillageStatisticItem::query()->where('is_active', true)->count('*'),
                'published_label' => 'aktif',
            ],
        ];

        return view('livewire.dashboard.index', [
            'stats' => $stats,
        ]);
    }
}
