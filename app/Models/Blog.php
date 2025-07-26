<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Blog extends Model
{
    protected $table = 'blogs';

    protected $fillable = ['title', 'content', 'image', 'privet', 'user_id'];
    protected $casts = [
        'privet' => 'boolean',
    ];
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function getImageUrlAttribute()
    {
        return Storage::url($this->image);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
