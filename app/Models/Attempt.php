<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Attempt extends Model
{
    protected $fillable = [
        'quiz_id', 'student_id', 'status',
        'score', 'total_marks', 'started_at', 'submitted_at'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function getScorePercentageAttribute()
    {
        if (!$this->total_marks) return 0;
        return round(($this->score / $this->total_marks) * 100);
    }
}