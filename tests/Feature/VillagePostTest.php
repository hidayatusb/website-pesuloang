<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VillagePost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VillagePostTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\VillageIdentitySeeder::class);
        $this->seed(\Database\Seeders\VillageStatisticSeeder::class);
        $this->seed(\Database\Seeders\VillagePostSeeder::class);
    }

    public function test_home_page_shows_published_posts(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Pembangunan Jalan Desa Sukamaju Tahap 2');
    }

    public function test_public_berita_index_is_accessible(): void
    {
        $response = $this->get('/berita');

        $response->assertStatus(200);
        $response->assertSee('Informasi Terkini');
        $response->assertSee('Festival Budaya Desa Sukamaju 2024');
    }

    public function test_public_berita_detail_is_accessible(): void
    {
        $post = VillagePost::query()->first();

        $response = $this->get('/berita/'.$post->slug);

        $response->assertStatus(200);
        $response->assertSee($post->title);
        $response->assertSee('Bagikan');
        $response->assertSee('Salin Link');
    }

    public function test_guest_cannot_access_admin_berita(): void
    {
        $response = $this->get('/dashboard/desa/berita');

        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_berita_index(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard/desa/berita');

        $response->assertStatus(200);
        $response->assertSee('Berita', false);
    }

    public function test_admin_can_access_create_form(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard/desa/berita/tambah');

        $response->assertStatus(200);
        $response->assertSee('Tambah Konten');
    }

    public function test_admin_can_toggle_post_publish_status(): void
    {
        $user = User::factory()->create();
        $post = VillagePost::query()->first();
        $originalStatus = $post->is_published;

        $response = $this->actingAs($user)->patch(route('desa.berita.toggle', $post));

        $response->assertRedirect();
        $this->assertNotSame($originalStatus, $post->fresh()->is_published);
    }

    public function test_admin_can_delete_post(): void
    {
        $user = User::factory()->create();
        $post = VillagePost::query()->first();

        $response = $this->actingAs($user)->delete(route('desa.berita.destroy', $post));

        $response->assertRedirect();
        $this->assertDatabaseMissing('village_posts', ['id' => $post->id]);
    }
}
