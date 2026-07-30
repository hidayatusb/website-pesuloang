<?php

namespace App\Http\Controllers;

use App\Models\VillageIdentity;
use App\Models\VillageService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->query('kategori');

        $services = VillageService::query()
            ->published()
            ->when(
                $category && array_key_exists($category, VillageService::categories()),
                fn ($q) => $q->where('category', $category)
            )
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->paginate(9);

        return view('pages.layanan.index', [
            'identity' => VillageIdentity::current(),
            'services' => $services,
            'categories' => VillageService::categories(),
            'activeCategory' => $category,
        ]);
    }

    public function show(string $slug): View
    {
        $service = VillageService::query()
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        return view('pages.layanan.show', [
            'identity' => VillageIdentity::current(),
            'service' => $service,
            'relatedServices' => $service->relatedServices(),
        ]);
    }
}
