<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VillageIdentity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VillageIdentityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\VillageIdentitySeeder::class);
        $this->seed(\Database\Seeders\VillageStatisticSeeder::class);
    }

    public function test_home_page_displays_village_identity(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Desa Sukamaju');
        $response->assertSee('Kec. Cikarang Utara, Kab. Bekasi');
    }

    public function test_guest_cannot_access_identity_settings(): void
    {
        $response = $this->get('/dashboard/desa/identitas');

        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_identity_settings(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard/desa/identitas');

        $response->assertStatus(200);
        $response->assertSee('Identitas Desa');
        $response->assertSee('Nama Desa');
    }

    public function test_village_identity_current_returns_seeded_data(): void
    {
        VillageIdentity::clearCache();

        $identity = VillageIdentity::current();

        $this->assertEquals('Desa Sukamaju', $identity->name);
        $this->assertEquals('2.350 Jiwa', $identity->population);
    }
}
