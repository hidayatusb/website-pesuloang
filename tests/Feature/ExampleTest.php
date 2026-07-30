<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\VillageIdentitySeeder::class);
        $this->seed(\Database\Seeders\VillageStatisticSeeder::class);
        $this->seed(\Database\Seeders\VillagePostSeeder::class);
    }

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Desa Sukamaju');

        $response = $this->get('/login');
        $response->assertStatus(200);
    }
}
