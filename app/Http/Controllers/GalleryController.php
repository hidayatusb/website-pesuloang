<?php

namespace App\Http\Controllers;

use App\Models\VillageGallery;
use App\Models\VillageIdentity;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        return view('pages.galeri.index', [
            'identity' => VillageIdentity::current(),
            'galleries' => VillageGallery::query()
                ->latestPublished()
                ->paginate(12),
        ]);
    }
}
