<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VillageOfficial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class VillageOfficialController extends Controller
{
    public function toggle(VillageOfficial $official): RedirectResponse
    {
        $official->update(['is_active' => ! $official->is_active]);

        $status = $official->is_active ? 'ditampilkan' : 'disembunyikan';

        return back()->with('success', "Aparatur berhasil {$status}.");
    }

    public function destroy(VillageOfficial $official): RedirectResponse
    {
        if ($official->photo_path && ! str_starts_with($official->photo_path, 'assets/')) {
            Storage::disk('public')->delete($official->photo_path);
        }

        $official->delete();

        return back()->with('success', 'Aparatur berhasil dihapus.');
    }
}
