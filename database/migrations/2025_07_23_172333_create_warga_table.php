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
    Schema::create('warga', function (Blueprint $table) {
    $table->id();
    $table->string('nik')->unique();
    $table->string('no_kk');
    $table->string('nama');
    $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
    $table->date('tanggal_lahir');
    $table->string('tempat_lahir');
    $table->string('agama');
    $table->enum('status_kependudukkan', ['Warga', 'Pendatang', 'Pindah', 'Kematian', 'Kelahiran']);
    $table->timestamps();

    $table->foreign('no_kk')->references('no_kk')->on('kartu_keluarga')->onDelete('cascade');
});

}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warga');
    }
};
