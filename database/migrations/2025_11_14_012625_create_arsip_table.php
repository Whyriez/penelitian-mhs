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
        Schema::create('arsip', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('nama');

            $table->string('nama_lembaga');
            $table->string('tempat_penelitian');
            $table->string('nomor_surat');
            $table->date('tgl_surat');

            $table->string('nomor_izin')->nullable();
            $table->date('tgl_terbit')->nullable();

            $table->json('file');

            $table->enum('status', ['pending', 'valid', 'revisi'])
                ->default('pending');

            $table->text('catatan_revisi')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip');
    }
};
