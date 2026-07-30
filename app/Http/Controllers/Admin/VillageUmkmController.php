<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VillageUmkm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class VillageUmkmController extends Controller
{
    public function toggle(VillageUmkm $umkm): RedirectResponse
    {
        $umkm->update([
            'is_published' => ! $umkm->is_published,
            'published_at' => ! $umkm->is_published ? ($umkm->published_at ?? now()) : $umkm->published_at,
        ]);

        $status = $umkm->is_published ? 'dipublikasikan' : 'disembunyikan';

        return back()->with('success', "UMKM berhasil {$status}.");
    }

    public function destroy(VillageUmkm $umkm): RedirectResponse
    {
        if ($umkm->image_path && ! str_starts_with($umkm->image_path, 'assets/')) {
            Storage::disk('public')->delete($umkm->image_path);
        }

        $umkm->delete();

        return back()->with('success', 'UMKM berhasil dihapus.');
    }
}
