<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'comment',
        'reply_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'model');
    }

    public function userReactions()
    {
        return $this->morphMany(UserReaction::class, 'reactable');
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'reply_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'reply_id');
    }
}
