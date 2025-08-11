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
       Schema::create('kelahiran', function (Blueprint $table) {
        $table->id();
        $table->string('no_kk');
        $table->string('nama_bayi');
        $table->enum('jenis_kelamin', ['L', 'P']);
        $table->date('tanggal_lahir');
        $table->string('tempat_lahir');
        $table->string('nik_ayah');
        $table->string('nik_ibu');
        $table->timestamps();

        $table->foreign('nik_ayah')->references('nik')->on('warga')->onDelete('cascade');
        $table->foreign('nik_ibu')->references('nik')->on('warga')->onDelete('cascade');
        $table->foreign('no_kk')->references('no_kk')->on('kartu_keluarga')->onDelete('cascade');
       
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelahiran');
    }
};
