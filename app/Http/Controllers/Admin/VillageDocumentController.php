<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VillageDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class VillageDocumentController extends Controller
{
    public function toggle(VillageDocument $document): RedirectResponse
    {
        $document->update([
            'is_published' => ! $document->is_published,
            'published_at' => ! $document->is_published ? ($document->published_at ?? now()) : $document->published_at,
        ]);

        $status = $document->is_published ? 'dipublikasikan' : 'disembunyikan';

        return back()->with('success', "Dokumen berhasil {$status}.");
    }

    public function destroy(VillageDocument $document): RedirectResponse
    {
        if ($document->file_path && ! str_starts_with($document->file_path, 'assets/')) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }
}
