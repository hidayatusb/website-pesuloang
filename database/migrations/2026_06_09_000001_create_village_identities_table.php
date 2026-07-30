<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('village_identities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('kecamatan');
            $table->string('kabupaten');
            $table->string('logo_path')->nullable();
            $table->string('welcome_text')->default('Selamat Datang di');
            $table->text('tagline');
            $table->string('hero_image_path')->nullable();
            $table->string('population');
            $table->string('households');
            $table->string('area');
            $table->string('hamlets');
            $table->string('about_label')->default('Tentang Kami');
            $table->string('about_title');
            $table->text('about_description');
            $table->string('about_image_path')->nullable();
            $table->text('address');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('village_identities');
    }
};
