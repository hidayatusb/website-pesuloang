<?php

namespace App\Http\Controllers;

use App\Models\VillageIdentity;
use App\Models\VillageOfficial;
use App\Models\VillagePost;
use App\Models\VillageService;
use App\Models\VillageStatisticCategory;
use App\Models\VillageUmkm;

class HomeController extends Controller
{
    public function index()
    {
        return view('pages.home', [
            'identity' => VillageIdentity::current(),
            'statistics' => VillageStatisticCategory::homeHighlights(),
            'latestPosts' => VillagePost::query()->with('author')->latestPublished()->limit(3)->get(),
            'featuredUmkms' => VillageUmkm::query()->featured()->limit(3)->get(),
            'featuredServices' => VillageService::query()->featured()->limit(4)->get(),
            'officials' => VillageOfficial::query()->active()->get(),
        ]);
    }
}
