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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('meeting_id')->constrained()->onDelete('cascade'); 
            $table->string('name'); 
            $table->string('email')->unique(); 
            $table->string('phone')->unique(); 
            $table->string('position'); 
            $table->string('project_or_product'); 
            $table->string('pic'); 
            $table->unsignedTinyInteger('pertanyaan1'); 
            $table->unsignedTinyInteger('pertanyaan2'); 
            $table->unsignedTinyInteger('pertanyaan3'); 
            $table->unsignedTinyInteger('pertanyaan4'); 
            $table->unsignedTinyInteger('pertanyaan5');
            $table->integer('pertanyaan6');
            $table->integer('pertanyaan7');
            $table->integer('pertanyaan8');
            $table->text('suggestions2')->nullable();
            $table->text('suggestions')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
