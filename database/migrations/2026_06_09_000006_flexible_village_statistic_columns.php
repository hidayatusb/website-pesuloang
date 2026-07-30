<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('village_statistic_categories', function (Blueprint $table) {
            $table->json('columns')->nullable()->after('description');
            $table->string('chart_label_key')->default('indikator')->after('columns');
            $table->string('chart_value_key')->default('nilai')->after('chart_label_key');
        });

        Schema::table('village_statistic_items', function (Blueprint $table) {
            $table->json('data')->nullable()->after('village_statistic_category_id');
        });

        $defaultColumns = json_encode([
            ['key' => 'indikator', 'label' => 'Indikator', 'type' => 'text', 'required' => true],
            ['key' => 'nilai', 'label' => 'Nilai', 'type' => 'number', 'required' => true],
            ['key' => 'satuan', 'label' => 'Satuan', 'type' => 'text', 'required' => false],
            ['key' => 'periode', 'label' => 'Periode', 'type' => 'text', 'required' => false],
        ]);

        DB::table('village_statistic_categories')->update([
            'columns' => $defaultColumns,
            'chart_label_key' => 'indikator',
            'chart_value_key' => 'nilai',
        ]);

        foreach (DB::table('village_statistic_items')->get() as $item) {
            DB::table('village_statistic_items')
                ->where('id', $item->id)
                ->update([
                    'data' => json_encode([
                        'indikator' => $item->indicator,
                        'nilai' => $item->value,
                        'satuan' => $item->unit,
                        'periode' => $item->period,
                    ]),
                ]);
        }

        Schema::table('village_statistic_items', function (Blueprint $table) {
            $table->dropColumn(['indicator', 'value', 'unit', 'period']);
        });
    }

    public function down(): void
    {
        Schema::table('village_statistic_items', function (Blueprint $table) {
            $table->string('indicator')->after('village_statistic_category_id');
            $table->string('value')->after('indicator');
            $table->string('unit')->nullable()->after('value');
            $table->string('period')->nullable()->after('unit');
        });

        foreach (DB::table('village_statistic_items')->get() as $item) {
            $data = json_decode($item->data, true) ?? [];

            DB::table('village_statistic_items')
                ->where('id', $item->id)
                ->update([
                    'indicator' => $data['indikator'] ?? '',
                    'value' => $data['nilai'] ?? '',
                    'unit' => $data['satuan'] ?? null,
                    'period' => $data['periode'] ?? null,
                ]);
        }

        Schema::table('village_statistic_items', function (Blueprint $table) {
            $table->dropColumn('data');
        });

        Schema::table('village_statistic_categories', function (Blueprint $table) {
            $table->dropColumn(['columns', 'chart_label_key', 'chart_value_key']);
        });
    }
};
