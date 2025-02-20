<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('project_management', function (Blueprint $table) {
            $table->id();
            $table->string('nama_proyek');  // Sesuaikan dengan 'nama_proyek' seperti yang digunakan di seeder
            $table->text('description')->nullable();  // Menggunakan 'description' sesuai seeder
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->date('deadline')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('project_management');
    }
};
