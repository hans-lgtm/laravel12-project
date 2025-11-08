<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('access_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Kode unik
            $table->boolean('is_used')->default(false); // Status apakah sudah digunakan
            $table->timestamp('used_at')->nullable(); // Waktu digunakan
            $table->string('used_by_ip')->nullable(); // IP yang menggunakan
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('access_codes');
    }
};