<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_toko');
            $table->string('nama');
            $table->string('nomor');
            $table->text('tentang')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('logo')->default('logo.jpg');
            $table->string('logo_login')->default('login_login.jpg');
            $table->string('favicon')->default('favicon.jpg');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
