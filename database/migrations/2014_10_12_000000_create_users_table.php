<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('users', function (Blueprint $table) {
        $table->id(); // Primary Key auto-increment
        $table->string('name'); // Nama pengguna
        $table->string('email')->unique(); // Email unik
        $table->timestamp('email_verified_at')->nullable(); // Email verifikasi, bisa null
        $table->string('password'); // Password
        $table->string('role')->default('user'); // Role default 'user', bisa 'admin', 'superadmin'
        $table->rememberToken(); // Token untuk remember me saat login
        $table->timestamps(); // created_at dan updated_at otomatis
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
