<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['content', 'user_id', 'blog_id'];

    function user()
    {
        return $this->belongsTo(User::class);
    }

    function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}