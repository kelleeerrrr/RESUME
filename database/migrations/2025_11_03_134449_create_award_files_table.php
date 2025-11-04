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
        Schema::create('award_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resume_id');
            $table->string('award_name');
            $table->string('file_path')->nullable(); // stores uploaded certificate path
            $table->timestamps();

            // Foreign key to resume table
            $table->foreign('resume_id')->references('id')->on('resumes')->onDelete('cascade');
            
            // Optional: prevent duplicate awards per resume
            $table->unique(['resume_id', 'award_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('award_files');
    }
};
