<?php

namespace App\Http\Controllers;

use App\Models\VillageIdentity;
use App\Models\VillageInfographic;
use Illuminate\View\View;

class InfographicController extends Controller
{
    public function index(): View
    {
        return view('pages.infografis.index', [
            'identity' => VillageIdentity::current(),
            'infographics' => VillageInfographic::query()
                ->latestPublished()
                ->paginate(9),
        ]);
    }
}
