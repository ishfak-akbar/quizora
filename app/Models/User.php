<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth'     => 'date',
        ];
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'teacher_id');
    }

    public function attempts()
    {
        return $this->hasMany(Attempt::class, 'student_id');
    }
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class, 'student_id');
    }
}
