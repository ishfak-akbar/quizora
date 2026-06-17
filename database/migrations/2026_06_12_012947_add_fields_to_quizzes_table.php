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
        Schema::table('quizzes', function (Blueprint $table) {
            $table->enum('visibility', ['public', 'private'])->default('private')->after('status');
            $table->string('category')->nullable()->after('visibility');
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium')->after('category');
            $table->string('tags')->nullable()->after('difficulty');
            $table->integer('passing_score')->nullable()->after('tags');
        });
    }

    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn(['visibility', 'category', 'difficulty', 'tags', 'passing_score']);
        });
    }
};
