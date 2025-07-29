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
        Schema::table('warga', function (Blueprint $table) {
            $table->foreignId('kartu_keluarga_id')->nullable()->constrained('kartu_keluarga')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('warga', function (Blueprint $table) {
            $table->dropForeign(['kartu_keluarga_id']);
            $table->dropColumn('kartu_keluarga_id');
        });
    }

};
