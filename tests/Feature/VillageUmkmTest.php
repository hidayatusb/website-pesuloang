<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VillageUmkm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VillageUmkmTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\VillageIdentitySeeder::class);
        $this->seed(\Database\Seeders\VillageStatisticSeeder::class);
        $this->seed(\Database\Seeders\VillagePostSeeder::class);
        $this->seed(\Database\Seeders\VillageUmkmSeeder::class);
    }

    public function test_home_page_shows_featured_umkms(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('UMKM Unggulan');
        $response->assertSee('Warung Makan Bu Siti');
    }

    public function test_public_umkm_index_is_accessible(): void
    {
        $response = $this->get('/umkm');

        $response->assertStatus(200);
        $response->assertSee('UMKM');
        $response->assertSee('Kerajinan Anyaman Bambu Pak Joko');
    }

    public function test_public_umkm_index_filters_by_category(): void
    {
        $response = $this->get('/umkm?kategori=kerajinan');

        $response->assertStatus(200);
        $response->assertSee('Kerajinan Anyaman Bambu Pak Joko');
        $response->assertDontSee('Warung Makan Bu Siti');
    }

    public function test_public_umkm_detail_is_accessible(): void
    {
        $umkm = VillageUmkm::query()->first();

        $response = $this->get('/umkm/'.$umkm->slug);

        $response->assertStatus(200);
        $response->assertSee($umkm->name);
        $response->assertSee($umkm->owner_name);
        $response->assertSee('Bagikan');
        $response->assertSee('Salin Link');
    }

    public function test_guest_cannot_access_admin_umkm(): void
    {
        $response = $this->get('/dashboard/desa/umkm');

        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_umkm_index(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard/desa/umkm');

        $response->assertStatus(200);
        $response->assertSee('UMKM Desa');
        $response->assertSee('Warung Makan Bu Siti');
    }

    public function test_admin_can_access_create_form(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard/desa/umkm/tambah');

        $response->assertStatus(200);
        $response->assertSee('Tambah UMKM');
    }

    public function test_admin_can_toggle_umkm_publish_status(): void
    {
        $user = User::factory()->create();
        $umkm = VillageUmkm::query()->first();
        $originalStatus = $umkm->is_published;

        $response = $this->actingAs($user)->patch(route('desa.umkm.toggle', $umkm));

        $response->assertRedirect();
        $this->assertNotSame($originalStatus, $umkm->fresh()->is_published);
    }

    public function test_admin_can_delete_umkm(): void
    {
        $user = User::factory()->create();
        $umkm = VillageUmkm::query()->first();

        $response = $this->actingAs($user)->delete(route('desa.umkm.destroy', $umkm));

        $response->assertRedirect();
        $this->assertDatabaseMissing('village_umkms', ['id' => $umkm->id]);
    }
}
