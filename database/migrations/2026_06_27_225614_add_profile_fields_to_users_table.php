<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->date('date_of_birth')->nullable()->after('phone');
            $table->enum('gender', ['male', 'female', 'other', 'prefer_not_to_say'])->nullable()->after('date_of_birth');
            $table->string('location')->nullable()->after('gender');
            $table->text('bio')->nullable()->after('location');
            $table->string('institution')->nullable()->after('bio');
            $table->string('class_level')->nullable()->after('institution');
            $table->enum('education_level', ['ssc', 'hsc', 'bachelor', 'master', 'other'])->nullable()->after('class_level');
            $table->enum('study_goal', ['exam_prep', 'self_learning', 'bcs', 'university_admission', 'other'])->nullable()->after('education_level');
            $table->string('preparing_for')->nullable()->after('study_goal');
            $table->enum('preferred_language', ['english', 'bangla'])->default('english')->after('preparing_for');
            $table->integer('target_score')->nullable()->after('preferred_language');
            $table->string('avatar_color')->default('#4F46E5')->after('target_score');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'date_of_birth',
                'gender',
                'location',
                'bio',
                'institution',
                'class_level',
                'education_level',
                'study_goal',
                'preparing_for',
                'preferred_language',
                'target_score',
                'avatar_color',
            ]);
        });
    }
};
