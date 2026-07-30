<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VillageStatisticCategory;
use App\Models\VillageStatisticItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VillageStatisticTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\VillageIdentitySeeder::class);
        $this->seed(\Database\Seeders\VillageStatisticSeeder::class);
    }

    public function test_home_page_displays_highlight_statistics(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('2.350');
        $response->assertSee('812');
    }

    public function test_public_statistika_page_has_bps_like_layout(): void
    {
        $response = $this->get('/statistika?kategori=penduduk');

        $response->assertStatus(200);
        $response->assertSee('Kategori Statistik');
        $response->assertSee('Penduduk');
        $response->assertSee('Laki-laki');
        $response->assertSee('1.180');
        $response->assertSee('public-statistic-chart-', false);
        $response->assertSee('data-statistic-chart-type="bar"', false);
        $response->assertSee('data-statistic-chart-type="pie"', false);
    }

    public function test_guest_cannot_access_statistika_settings(): void
    {
        $response = $this->get('/dashboard/desa/statistika');

        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_statistika_settings(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard/desa/statistika');

        $response->assertStatus(200);
        $response->assertSee('Statistika Desa');
        $response->assertSee('Kategori Statistik');
        $response->assertSee('Ringkasan Desa');
        $response->assertSee('Tambah Data Statistik');
        $response->assertSee('Indikator');
    }

    public function test_for_display_returns_only_active_categories_with_items(): void
    {
        VillageStatisticCategory::clearCache();

        $category = VillageStatisticCategory::query()->where('slug', 'pendidikan')->first();
        $category->update(['is_active' => false]);

        VillageStatisticCategory::clearCache();

        $categories = VillageStatisticCategory::forDisplay();

        $this->assertFalse($categories->pluck('slug')->contains('pendidikan'));
        $this->assertTrue($categories->pluck('slug')->contains('penduduk'));
    }

    public function test_home_highlights_use_show_on_home_category(): void
    {
        VillageStatisticCategory::clearCache();

        $highlights = VillageStatisticCategory::homeHighlights();

        $this->assertGreaterThan(0, $highlights->count());
        $this->assertTrue($highlights->contains(fn (VillageStatisticItem $item) => $item->homeLabel() === 'Jumlah Penduduk'));
    }

    public function test_category_supports_custom_columns(): void
    {
        $category = VillageStatisticCategory::query()->create([
            'name' => 'Kesehatan',
            'slug' => 'kesehatan',
            'icon' => 'ki-heart',
            'columns' => [
                ['key' => 'fasilitas', 'label' => 'Fasilitas', 'type' => 'text', 'required' => true],
                ['key' => 'jumlah', 'label' => 'Jumlah', 'type' => 'number', 'required' => true],
                ['key' => 'tahun', 'label' => 'Tahun', 'type' => 'text', 'required' => false],
            ],
            'chart_label_key' => 'fasilitas',
            'chart_value_key' => 'jumlah',
            'sort_order' => 10,
            'is_active' => true,
        ]);

        $item = $category->items()->create([
            'data' => [
                'fasilitas' => 'Puskesmas Pembantu',
                'jumlah' => '2',
                'tahun' => '2024',
            ],
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->assertSame('Puskesmas Pembantu', $item->valueFor('fasilitas'));
        $this->assertSame(2.0, $item->chartValueFor('jumlah'));

        $dataset = $category->fresh()->chartDataset();
        $this->assertSame('simple', $dataset['mode']);
        $this->assertCount(1, $dataset['rows']);
    }

    public function test_grouped_chart_uses_all_numeric_columns(): void
    {
        $category = VillageStatisticCategory::query()->create([
            'name' => 'Status Perkawinan',
            'slug' => 'status-perkawinan-test',
            'icon' => 'ki-people',
            'columns' => [
                ['key' => 'indikator', 'label' => 'Kelompok', 'type' => 'text', 'required' => true],
                ['key' => 'nilai', 'label' => 'Laki-laki', 'type' => 'number', 'required' => true],
                ['key' => 'satuan', 'label' => 'Perempuan', 'type' => 'number', 'required' => true],
            ],
            'chart_label_key' => 'indikator',
            'chart_value_key' => 'nilai',
            'sort_order' => 11,
            'is_active' => true,
        ]);

        $category->items()->createMany([
            [
                'data' => ['indikator' => 'Belum Kawin', 'nilai' => '10', 'satuan' => '5'],
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'data' => ['indikator' => 'Kawin', 'nilai' => '20', 'satuan' => '21'],
                'sort_order' => 2,
                'is_active' => true,
            ],
        ]);

        $dataset = $category->fresh()->chartDataset();

        $this->assertSame('grouped', $dataset['mode']);
        $this->assertSame(['Belum Kawin', 'Kawin'], $dataset['categories']);
        $this->assertSame('Laki-laki', $dataset['series'][0]['name']);
        $this->assertSame([10.0, 20.0], $dataset['series'][0]['data']);
        $this->assertSame('Perempuan', $dataset['series'][1]['name']);
        $this->assertSame([5.0, 21.0], $dataset['series'][1]['data']);
        $this->assertTrue($category->chartHasData());
    }
}
