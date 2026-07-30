<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('village_statistic_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->default('ki-chart');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('show_on_home')->default(false);
            $table->timestamps();
        });

        Schema::create('village_statistic_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('village_statistic_category_id')->constrained()->cascadeOnDelete();
            $table->string('indicator');
            $table->string('value');
            $table->string('unit')->nullable();
            $table->string('period')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        if (Schema::hasTable('village_statistics') && DB::table('village_statistics')->exists()) {
            $summaryId = DB::table('village_statistic_categories')->insertGetId([
                'name' => 'Ringkasan Desa',
                'slug' => 'ringkasan-desa',
                'icon' => 'ki-chart-line',
                'description' => 'Indikator utama desa yang ditampilkan di halaman beranda.',
                'sort_order' => 1,
                'is_active' => true,
                'show_on_home' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach (DB::table('village_statistics')->orderBy('sort_order')->get() as $row) {
                DB::table('village_statistic_items')->insert([
                    'village_statistic_category_id' => $summaryId,
                    'indicator' => $row->label,
                    'value' => $row->value,
                    'unit' => null,
                    'period' => null,
                    'sort_order' => $row->sort_order,
                    'is_active' => $row->is_active,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

        }

        Schema::dropIfExists('village_statistics');
    }

    public function down(): void
    {
        Schema::create('village_statistics', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('value');
            $table->string('icon')->default('ki-chart');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::dropIfExists('village_statistic_items');
        Schema::dropIfExists('village_statistic_categories');
    }
};
