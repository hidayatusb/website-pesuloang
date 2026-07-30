<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VillageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VillageServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\VillageIdentitySeeder::class);
        $this->seed(\Database\Seeders\VillageStatisticSeeder::class);
        $this->seed(\Database\Seeders\VillagePostSeeder::class);
        $this->seed(\Database\Seeders\VillageUmkmSeeder::class);
        $this->seed(\Database\Seeders\VillageServiceSeeder::class);
    }

    public function test_home_page_shows_featured_services(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Layanan Publik');
        $response->assertSee('Surat Pengantar');
    }

    public function test_public_layanan_index_is_accessible(): void
    {
        $response = $this->get('/layanan');

        $response->assertStatus(200);
        $response->assertSee('Layanan');
        $response->assertSee('Surat Keterangan Domisili');
    }

    public function test_public_layanan_index_filters_by_category(): void
    {
        $response = $this->get('/layanan?kategori=surat');

        $response->assertStatus(200);
        $response->assertSee('Surat Pengantar');
        $response->assertDontSee('Pengaduan & Aspirasi Warga');
    }

    public function test_public_layanan_detail_shows_requirements_and_procedures(): void
    {
        $service = VillageService::query()->first();

        $response = $this->get('/layanan/'.$service->slug);

        $response->assertStatus(200);
        $response->assertSee($service->title);
        $response->assertSee('Persyaratan');
        $response->assertSee('Prosedur');
        $response->assertSee('Bagikan');
    }

    public function test_guest_cannot_access_admin_layanan(): void
    {
        $response = $this->get('/dashboard/desa/layanan');

        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_layanan_index(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard/desa/layanan');

        $response->assertStatus(200);
        $response->assertSee('Layanan Desa');
        $response->assertSee('Surat Pengantar');
    }

    public function test_admin_can_access_create_form(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard/desa/layanan/tambah');

        $response->assertStatus(200);
        $response->assertSee('Tambah Layanan');
        $response->assertSee('Persyaratan');
        $response->assertSee('Prosedur');
        $response->assertSee('data-rich-editor', false);
        $response->assertSee('rich-editor', false);
    }

    public function test_admin_can_toggle_layanan_publish_status(): void
    {
        $user = User::factory()->create();
        $service = VillageService::query()->first();
        $originalStatus = $service->is_published;

        $response = $this->actingAs($user)->patch(route('desa.layanan.toggle', $service));

        $response->assertRedirect();
        $this->assertNotSame($originalStatus, $service->fresh()->is_published);
    }

    public function test_admin_can_delete_layanan(): void
    {
        $user = User::factory()->create();
        $service = VillageService::query()->first();

        $response = $this->actingAs($user)->delete(route('desa.layanan.destroy', $service));

        $response->assertRedirect();
        $this->assertDatabaseMissing('village_services', ['id' => $service->id]);
    }
}
