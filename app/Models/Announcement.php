<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'body',
        'audience',
        'type',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function reads()
    {
        return $this->hasMany(\App\Models\AnnouncementRead::class);
    }

    public function isReadBy($userId): bool
    {
        return $this->reads()->where('user_id', $userId)->exists();
    }
}
