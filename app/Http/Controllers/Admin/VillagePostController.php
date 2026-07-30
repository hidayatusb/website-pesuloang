<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VillagePost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class VillagePostController extends Controller
{
    public function toggle(VillagePost $post): RedirectResponse
    {
        $post->update([
            'is_published' => ! $post->is_published,
            'published_at' => ! $post->is_published ? now() : $post->published_at,
        ]);

        $status = $post->is_published ? 'dipublikasikan' : 'disembunyikan';

        return back()->with('success', "Konten berhasil {$status}.");
    }

    public function destroy(VillagePost $post): RedirectResponse
    {
        if ($post->image_path && ! str_starts_with($post->image_path, 'assets/')) {
            Storage::disk('public')->delete($post->image_path);
        }

        $post->delete();

        return back()->with('success', 'Berita berhasil dihapus.');
    }
}
