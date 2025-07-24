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
       Schema::create('perpindahan', function (Blueprint $table) {
        $table->id();
        $table->string('nik');
        $table->string('nama');
        $table->string('alamat_baru');
        $table->date('tanggal_pindah');
        $table->timestamps();

        $table->foreign('nik')->references('nik')->on('warga')->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perpindahan');
    }
};
