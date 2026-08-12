@extends('layouts.public')

@section('title', 'Dokumen — ' . $identity->name)

@section('content')
<section class="py-14 lg:py-16">
    <div class="mx-auto max-w-5xl px-4 lg:px-8">
        <div class="mb-10">
            <p class="mb-2 text-sm font-semibold uppercase tracking-wide text-desa-600">Transparansi Desa</p>
            <h1 class="text-2xl font-bold text-gray-900 lg:text-3xl">Dokumen {{ $identity->name }}</h1>
            <p class="mt-2 text-gray-600">Unduh dokumen resmi desa: peraturan, laporan, formulir, dan dokumen publik lainnya.</p>
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="w-12 px-4 py-3 text-left font-semibold text-gray-700">No</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Judul Dokumen</th>
                            <th class="w-24 px-4 py-3 text-left font-semibold text-gray-700">Jenis</th>
                            <th class="w-24 px-4 py-3 text-left font-semibold text-gray-700">Ukuran</th>
                            <th class="w-36 px-4 py-3 text-left font-semibold text-gray-700">Tanggal</th>
                            <th class="w-32 px-4 py-3 text-right font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($documents as $document)
                            <tr class="hover:bg-gray-50/80">
                                <td class="px-4 py-3 text-gray-500">
                                    {{ $documents->firstItem() + $loop->index }}
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $document->title }}</td>
                                <td class="px-4 py-3">
                                    <span @class([
                                        'inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold',
                                        'bg-red-50 text-red-700' => $document->fileExtension() === 'pdf',
                                        'bg-sky-50 text-sky-700' => $document->fileExtension() !== 'pdf',
                                    ])>
                                        {{ $document->fileTypeLabel() }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-500">{{ $document->formattedSize() }}</td>
                                <td class="px-4 py-3 text-gray-500">{{ $document->formattedDate() }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ $document->fileUrl() }}" target="_blank" download
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-desa-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-desa-700">
                                        <i class="ki-filled ki-file-down text-sm"></i>
                                        Unduh
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-gray-500">
                                    Belum ada dokumen yang dipublikasikan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($documents->hasPages())
            <div class="mt-8">
                {{ $documents->links() }}
            </div>
        @endif
    </div>
</section>
@endsection
