<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('village_posts', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('published_at')->constrained()->nullOnDelete();
            $table->string('author_name')->nullable()->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('village_posts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn('author_name');
        });
    }
};
