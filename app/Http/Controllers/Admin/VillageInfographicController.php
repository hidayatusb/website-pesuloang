<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VillageInfographic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class VillageInfographicController extends Controller
{
    public function toggle(VillageInfographic $infographic): RedirectResponse
    {
        $infographic->update([
            'is_published' => ! $infographic->is_published,
            'published_at' => ! $infographic->is_published ? ($infographic->published_at ?? now()) : $infographic->published_at,
        ]);

        $status = $infographic->is_published ? 'dipublikasikan' : 'disembunyikan';

        return back()->with('success', "Infografis berhasil {$status}.");
    }

    public function destroy(VillageInfographic $infographic): RedirectResponse
    {
        if ($infographic->image_path && ! str_starts_with($infographic->image_path, 'assets/')) {
            Storage::disk('public')->delete($infographic->image_path);
        }

        $infographic->delete();

        return back()->with('success', 'Infografis berhasil dihapus.');
    }
}
