<?php

namespace Database\Seeders;

use App\Models\VillageService;
use Illuminate\Database\Seeder;

class VillageServiceSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'title' => 'Surat Pengantar',
                'slug' => 'surat-pengantar',
                'category' => 'surat',
                'icon' => 'ki-document',
                'excerpt' => 'Pengajuan surat pengantar untuk keperluan administrasi warga.',
                'description' => '<p>Layanan pembuatan surat pengantar dari pemerintah desa untuk keperluan administrasi di instansi lain.</p>',
                'requirements' => '<ul><li>KTP asli dan fotokopi pemohon</li><li>Kartu Keluarga (KK)</li><li>Surat permohonan bermaterai</li><li>Melengkapi formulir pengajuan di kantor desa</li></ul>',
                'procedures' => '<ol><li>Datang ke kantor desa pada jam layanan (Senin–Jumat, 08.00–14.00 WIB)</li><li>Ambil dan isi formulir permohonan surat pengantar</li><li>Serahkan berkas persyaratan kepada petugas loket</li><li>Petugas melakukan verifikasi data dan pencatatan</li><li>Surat pengantar dapat diambil setelah proses selesai (maks. 3 hari kerja)</li></ol>',
                'is_published' => true,
                'is_featured' => true,
                'published_at' => '2024-05-10 08:00:00',
                'sort_order' => 1,
            ],
            [
                'title' => 'Surat Keterangan Domisili',
                'slug' => 'surat-keterangan-domisili',
                'category' => 'surat',
                'icon' => 'ki-notepad-edit',
                'excerpt' => 'Layanan pembuatan surat keterangan domisili bagi warga desa.',
                'description' => '<p>Surat keterangan domisili diterbitkan untuk membuktikan bahwa pemohon bertempat tinggal di wilayah desa.</p>',
                'requirements' => '<ul><li>KTP dan KK pemohon</li><li>Surat pengantar RT/RW</li><li>Formulir permohonan yang telah diisi</li><li>Pas foto 3x4 (2 lembar)</li></ul>',
                'procedures' => '<ol><li>Ajukan permohonan melalui loket pelayanan desa</li><li>Petugas memverifikasi domisili dengan Ketua RT/RW</li><li>Biaya administrasi dibayarkan sesuai ketentuan desa</li><li>Surat keterangan domisili ditandatangani Kepala Desa</li><li>Surat dapat diambil pada hari yang sama jika berkas lengkap</li></ol>',
                'is_published' => true,
                'is_featured' => true,
                'published_at' => '2024-05-11 08:00:00',
                'sort_order' => 2,
            ],
            [
                'title' => 'Permohonan Bantuan Sosial',
                'slug' => 'permohonan-bantuan-sosial',
                'category' => 'permohonan',
                'icon' => 'ki-clipboard',
                'excerpt' => 'Ajukan permohonan bantuan sosial desa untuk warga yang membutuhkan.',
                'description' => '<p>Layanan pencatatan dan pengajuan permohonan bantuan sosial yang dikelola pemerintah desa.</p>',
                'requirements' => '<ul><li>KTP dan KK pemohon</li><li>Surat keterangan tidak mampu dari RT/RW</li><li>Surat permohonan bermaterai</li><li>Dokumen pendukung sesuai jenis bantuan</li></ul>',
                'procedures' => '<ol><li>Isi formulir permohonan bantuan sosial di kantor desa</li><li>Serahkan berkas persyaratan lengkap</li><li>Tim verifikasi desa melakukan survei lapangan</li><li>Hasil seleksi diumumkan melalui papan informasi desa</li><li>Bantuan disalurkan sesuai jadwal yang ditentukan</li></ol>',
                'is_published' => true,
                'is_featured' => true,
                'published_at' => '2024-05-12 08:00:00',
                'sort_order' => 3,
            ],
            [
                'title' => 'Pengaduan & Aspirasi Warga',
                'slug' => 'pengaduan-aspirasi-warga',
                'category' => 'pengaduan',
                'icon' => 'ki-message-text-2',
                'excerpt' => 'Sampaikan aspirasi dan keluhan warga kepada pemerintah desa.',
                'description' => '<p>Saluran resmi untuk warga menyampaikan pengaduan, saran, dan aspirasi terkait pelayanan dan pembangunan desa.</p>',
                'requirements' => '<ul><li>Identitas pelapor (nama, alamat, nomor telepon)</li><li>Uraian pengaduan atau aspirasi secara jelas</li><li>Bukti pendukung jika ada (foto/dokumen)</li></ul>',
                'procedures' => '<ol><li>Sampaikan pengaduan langsung ke kantor desa atau melalui kontak resmi desa</li><li>Petugas mencatat dan memberikan nomor tiket pengaduan</li><li>Tim terkait melakukan tindak lanjut dan koordinasi</li><li>Progress penanganan diinformasikan kepada pelapor</li><li>Hasil penyelesaian dituangkan dalam laporan resmi desa</li></ol>',
                'is_published' => true,
                'is_featured' => true,
                'published_at' => '2024-05-13 08:00:00',
                'sort_order' => 4,
            ],
        ];

        foreach ($items as $item) {
            VillageService::query()->updateOrCreate(
                ['slug' => $item['slug']],
                $item
            );
        }
    }
}
