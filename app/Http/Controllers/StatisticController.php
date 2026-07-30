<?php

namespace App\Http\Controllers;

use App\Models\VillageIdentity;
use App\Models\VillageStatisticCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StatisticController extends Controller
{
    public function index(Request $request): View
    {
        $categories = VillageStatisticCategory::forDisplay();
        $activeSlug = $request->query('kategori', $categories->first()?->slug);
        $activeCategory = $categories->firstWhere('slug', $activeSlug) ?? $categories->first();

        return view('pages.statistika.index', [
            'identity' => VillageIdentity::current(),
            'categories' => $categories,
            'activeCategory' => $activeCategory,
        ]);
    }
}
