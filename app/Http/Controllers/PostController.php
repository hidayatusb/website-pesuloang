<?php

namespace App\Http\Controllers;

use App\Models\VillageIdentity;
use App\Models\VillagePost;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $type = $request->query('tipe');

        $posts = VillagePost::query()
            ->with('author')
            ->published()
            ->when(in_array($type, ['berita', 'pengumuman'], true), fn ($q) => $q->where('type', $type))
            ->orderByDesc('published_at')
            ->paginate(9);

        return view('pages.berita.index', [
            'identity' => VillageIdentity::current(),
            'posts' => $posts,
            'activeType' => $type,
        ]);
    }

    public function show(string $slug): View
    {
        $post = VillagePost::query()
            ->with('author')
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        return view('pages.berita.show', [
            'identity' => VillageIdentity::current(),
            'post' => $post,
            'relatedPosts' => $post->relatedPosts(),
        ]);
    }
}
