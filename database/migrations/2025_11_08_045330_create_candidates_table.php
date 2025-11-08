<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('chairman_name'); // Nama ketua
            $table->string('vice_chairman_name'); // Nama wakil ketua
            $table->string('number')->unique(); // Nomor paslon
            $table->text('vision');
            $table->text('mission');
            $table->string('chairman_photo')->nullable(); // Foto ketua
            $table->string('vice_chairman_photo')->nullable(); // Foto wakil ketua
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('candidates');
    }
};