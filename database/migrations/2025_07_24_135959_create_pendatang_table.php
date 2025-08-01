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
        Schema::create('pendatang', function (Blueprint $table) {
            $table->id();
            $table->string('nik'); 
            $table->string('alamat_lama');
            $table->date('tanggal_datang');

            
            $table->foreign('nik')->references('nik')->on('warga')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendatang');
    }
};
