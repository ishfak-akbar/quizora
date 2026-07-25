<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankQuestion extends Model
{
    protected $fillable = ['teacher_id', 'question_text', 'marks', 'category', 'tags'];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function options()
    {
        return $this->hasMany(BankOption::class)->orderBy('order');
    }
}
