<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VillageGallery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class VillageGalleryController extends Controller
{
    public function toggle(VillageGallery $gallery): RedirectResponse
    {
        $gallery->update([
            'is_published' => ! $gallery->is_published,
            'published_at' => ! $gallery->is_published ? ($gallery->published_at ?? now()) : $gallery->published_at,
        ]);

        $status = $gallery->is_published ? 'dipublikasikan' : 'disembunyikan';

        return back()->with('success', "Foto berhasil {$status}.");
    }

    public function destroy(VillageGallery $gallery): RedirectResponse
    {
        if ($gallery->image_path && ! str_starts_with($gallery->image_path, 'assets/')) {
            Storage::disk('public')->delete($gallery->image_path);
        }

        $gallery->delete();

        return back()->with('success', 'Foto berhasil dihapus.');
    }
}
