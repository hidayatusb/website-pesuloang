<?php

namespace App\Http\Controllers;

use App\Models\VillageIdentity;
use App\Models\VillageUmkm;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UmkmController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->query('kategori');

        $umkms = VillageUmkm::query()
            ->published()
            ->when(
                $category && array_key_exists($category, VillageUmkm::categories()),
                fn ($q) => $q->where('category', $category)
            )
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->paginate(9);

        return view('pages.umkm.index', [
            'identity' => VillageIdentity::current(),
            'umkms' => $umkms,
            'categories' => VillageUmkm::categories(),
            'activeCategory' => $category,
        ]);
    }

    public function show(string $slug): View
    {
        $umkm = VillageUmkm::query()
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        return view('pages.umkm.show', [
            'identity' => VillageIdentity::current(),
            'umkm' => $umkm,
            'relatedUmkms' => $umkm->relatedUmkms(),
        ]);
    }
}
