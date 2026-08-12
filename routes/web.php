<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\VillagePostController;
use App\Http\Controllers\Admin\VillageServiceController;
use App\Http\Controllers\Admin\VillageUmkmController;
use App\Http\Controllers\Admin\VillageDocumentController;
use App\Http\Controllers\Admin\VillageInfographicController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfographicController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\StatisticController;
use App\Http\Controllers\UmkmController;
use App\Livewire\Dashboard\Index as DashboardIndex;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/berita', [PostController::class, 'index'])->name('berita.index');
Route::get('/berita/{slug}', [PostController::class, 'show'])->name('berita.show');
Route::get('/statistika', [StatisticController::class, 'index'])->name('statistika.index');
Route::get('/statistika/unduh/{format}', [StatisticController::class, 'export'])
    ->whereIn('format', ['pdf', 'excel'])
    ->name('statistika.export');
Route::get('/umkm', [UmkmController::class, 'index'])->name('umkm.index');
Route::get('/umkm/{slug}', [UmkmController::class, 'show'])->name('umkm.show');
Route::get('/layanan', [ServiceController::class, 'index'])->name('layanan.index');
Route::get('/infografis', [InfographicController::class, 'index'])->name('infografis.index');
Route::get('/dokumen', [DocumentController::class, 'index'])->name('dokumen.index');
Route::get('/layanan/{slug}', [ServiceController::class, 'show'])->name('layanan.show');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', DashboardIndex::class)->name('dashboard.index');
    Route::livewire('/dashboard/desa/identitas', 'pages::desa.identitas.index')->name('desa.identitas.index');
    Route::livewire('/dashboard/desa/statistika', 'pages::desa.statistika.index')->name('desa.statistika.index');
    Route::livewire('/dashboard/desa/berita', 'pages::desa.berita.index')->name('desa.berita.index');
    Route::livewire('/dashboard/desa/berita/tambah', 'pages::desa.berita.form')->name('desa.berita.create');
    Route::livewire('/dashboard/desa/berita/{post}/edit', 'pages::desa.berita.form')->name('desa.berita.edit');
    Route::patch('/dashboard/desa/berita/{post}/toggle', [VillagePostController::class, 'toggle'])->name('desa.berita.toggle');
    Route::delete('/dashboard/desa/berita/{post}', [VillagePostController::class, 'destroy'])->name('desa.berita.destroy');
    Route::livewire('/dashboard/desa/umkm', 'pages::desa.umkm.index')->name('desa.umkm.index');
    Route::livewire('/dashboard/desa/umkm/tambah', 'pages::desa.umkm.form')->name('desa.umkm.create');
    Route::livewire('/dashboard/desa/umkm/{umkm}/edit', 'pages::desa.umkm.form')->name('desa.umkm.edit');
    Route::patch('/dashboard/desa/umkm/{umkm}/toggle', [VillageUmkmController::class, 'toggle'])->name('desa.umkm.toggle');
    Route::delete('/dashboard/desa/umkm/{umkm}', [VillageUmkmController::class, 'destroy'])->name('desa.umkm.destroy');
    Route::livewire('/dashboard/desa/layanan', 'pages::desa.layanan.index')->name('desa.layanan.index');
    Route::livewire('/dashboard/desa/layanan/tambah', 'pages::desa.layanan.form')->name('desa.layanan.create');
    Route::livewire('/dashboard/desa/layanan/{service}/edit', 'pages::desa.layanan.form')->name('desa.layanan.edit');
    Route::patch('/dashboard/desa/layanan/{service}/toggle', [VillageServiceController::class, 'toggle'])->name('desa.layanan.toggle');
    Route::delete('/dashboard/desa/layanan/{service}', [VillageServiceController::class, 'destroy'])->name('desa.layanan.destroy');
    Route::livewire('/dashboard/desa/infografis', 'pages::desa.infografis.index')->name('desa.infografis.index');
    Route::livewire('/dashboard/desa/infografis/tambah', 'pages::desa.infografis.form')->name('desa.infografis.create');
    Route::livewire('/dashboard/desa/infografis/{infographic}/edit', 'pages::desa.infografis.form')->name('desa.infografis.edit');
    Route::patch('/dashboard/desa/infografis/{infographic}/toggle', [VillageInfographicController::class, 'toggle'])->name('desa.infografis.toggle');
    Route::delete('/dashboard/desa/infografis/{infographic}', [VillageInfographicController::class, 'destroy'])->name('desa.infografis.destroy');
    Route::livewire('/dashboard/desa/dokumen', 'pages::desa.dokumen.index')->name('desa.dokumen.index');
    Route::livewire('/dashboard/desa/dokumen/tambah', 'pages::desa.dokumen.form')->name('desa.dokumen.create');
    Route::livewire('/dashboard/desa/dokumen/{document}/edit', 'pages::desa.dokumen.form')->name('desa.dokumen.edit');
    Route::patch('/dashboard/desa/dokumen/{document}/toggle', [VillageDocumentController::class, 'toggle'])->name('desa.dokumen.toggle');
    Route::delete('/dashboard/desa/dokumen/{document}', [VillageDocumentController::class, 'destroy'])->name('desa.dokumen.destroy');
});

Route::middleware(['guest'])->group(function () {
    Route::livewire('/login', 'pages::login.index')->name('login');
});
