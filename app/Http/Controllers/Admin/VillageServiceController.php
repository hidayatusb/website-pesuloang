<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VillageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class VillageServiceController extends Controller
{
    public function toggle(VillageService $service): RedirectResponse
    {
        $service->update([
            'is_published' => ! $service->is_published,
            'published_at' => ! $service->is_published ? ($service->published_at ?? now()) : $service->published_at,
        ]);

        $status = $service->is_published ? 'dipublikasikan' : 'disembunyikan';

        return back()->with('success', "Layanan berhasil {$status}.");
    }

    public function destroy(VillageService $service): RedirectResponse
    {
        if ($service->image_path && ! str_starts_with($service->image_path, 'assets/')) {
            Storage::disk('public')->delete($service->image_path);
        }

        $service->delete();

        return back()->with('success', 'Layanan berhasil dihapus.');
    }
}
