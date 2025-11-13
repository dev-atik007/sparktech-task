<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'comment'
    ];

    // Comment belongs to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Comment belongs to Post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
