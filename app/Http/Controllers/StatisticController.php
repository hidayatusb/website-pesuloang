<?php

namespace App\Http\Controllers;

use App\Exports\VillageStatisticExport;
use App\Models\VillageIdentity;
use App\Models\VillageStatisticCategory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;

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

    public function export(Request $request, string $format): Response
    {
        $categories = VillageStatisticCategory::forDisplay();
        $slug = $request->query('kategori');

        $selected = $slug
            ? $categories->where('slug', $slug)->values()
            : $categories->values();

        abort_if($selected->isEmpty(), 404);

        $identity = VillageIdentity::current();
        $filename = collect([
            'statistik',
            Str::slug($identity->name ?? 'desa'),
            $slug,
            now()->format('Y-m-d'),
        ])->filter()->implode('-');

        if ($format === 'excel') {
            return Excel::download(new VillageStatisticExport($selected), $filename.'.xlsx');
        }

        return Pdf::loadView('pages.statistika.pdf', [
            'identity' => $identity,
            'categories' => $selected,
            'generatedAt' => now(),
        ])->download($filename.'.pdf');
    }
}
