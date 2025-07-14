<?php

namespace App\Models;

use App\Observers\PostObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[ObservedBy([PostObserver::class])]
class Post extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'description',
        'pinned_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'pinned_at' => 'datetime',
    ];

    /**
     * Get the user that created the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user reactions for the post.
     */
    public function userReactions()
    {
        return $this->morphMany(UserReaction::class, 'reactable');
    }

    /**
     * Get the comments for the post.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Check if the post is pinned.
     */
    public function isPinned(): bool
    {
        return ! is_null($this->pinned_at);
    }

    /**
     * Pin the post.
     */
    public function pin(): void
    {
        $this->update(['pinned_at' => now()]);
    }

    /**
     * Unpin the post.
     */
    public function unpin(): void
    {
        $this->update(['pinned_at' => null]);
    }

    /**
     * Scope to get pinned posts.
     */
    public function scopePinned($query)
    {
        return $query->whereNotNull('pinned_at');
    }

    /**
     * Scope to get unpinned posts.
     */
    public function scopeUnpinned($query)
    {
        return $query->whereNull('pinned_at');
    }
}
