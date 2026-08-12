<?php

namespace App\Http\Controllers;

use App\Models\VillageDocument;
use App\Models\VillageIdentity;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function index(): View
    {
        return view('pages.dokumen.index', [
            'identity' => VillageIdentity::current(),
            'documents' => VillageDocument::query()
                ->latestPublished()
                ->paginate(15),
        ]);
    }
}
