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
    Schema::table('warga', function (Blueprint $table) {
        $table->string('hubungan_dalam_keluarga')->nullable();
    });
}

public function down(): void
{
    Schema::table('warga', function (Blueprint $table) {
        $table->dropColumn('hubungan_dalam_keluarga');
    });
}
};
