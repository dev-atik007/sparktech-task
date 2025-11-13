<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content'
    ];

    // post belongs to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // post has many likes
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // post has many comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
