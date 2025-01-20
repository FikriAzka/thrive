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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_rapat');
            $table->enum('jenis_rapat', ['offline', 'online']);
            $table->string('google_meet_link')->nullable();
            $table->string('google_event_id')->nullable();
            $table->text('agenda_rapat');
            $table->string('tempat_rapat')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_berakhir');
            $table->time('jam_mulai');
            $table->time('jam_berakhir');
            $table->text('catatan')->nullable();
            $table->string('nama_pic');
            $table->string('peserta');
            $table->enum('status', ['scheduled', 'completed', 'cancelled'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
        Schema::dropIfExists('meeting_participants');
    }
};
