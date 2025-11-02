<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resumes', function (Blueprint $table) {
            if (!Schema::hasColumn('resumes', 'organization')) {
                $table->json('organization')->nullable();
            }
            if (!Schema::hasColumn('resumes', 'interests')) {
                $table->json('interests')->nullable();
            }
            if (!Schema::hasColumn('resumes', 'education')) {
                $table->json('education')->nullable();
            }
            if (!Schema::hasColumn('resumes', 'skills')) {
                $table->json('skills')->nullable();
            }
            if (!Schema::hasColumn('resumes', 'projects')) {
                $table->json('projects')->nullable();
            }
            if (!Schema::hasColumn('resumes', 'awards')) {
                $table->json('awards')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('resumes', function (Blueprint $table) {
            $table->dropColumn([
                'organization',
                'interests',
                'education',
                'skills',
                'projects',
                'awards',
            ]);
        });
    }
};
