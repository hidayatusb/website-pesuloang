<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@desapesuloang.com',
            'password' => 'password',
        ]);

        $this->call(VillageIdentitySeeder::class);
        $this->call(VillageStatisticSeeder::class);
        $this->call(VillagePostSeeder::class);
        $this->call(VillageUmkmSeeder::class);
        $this->call(VillageServiceSeeder::class);
        $this->call(VillageInfographicSeeder::class);
    }
}
