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
       Schema::create('kematian', function (Blueprint $table) {
    $table->id();
    $table->string('nik');
    $table->date('tanggal_meninggal');
    $table->string('tempat_meninggal');
    $table->timestamps();

    $table->foreign('nik')->references('nik')->on('warga')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kematian');
    }
};
