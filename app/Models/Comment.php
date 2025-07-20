<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Likable;

class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory, Likable;

    protected $fillable = ['post_id', 'user_id', 'parent_id', 'content', 'is_approved'];

    /**
     * Get the post that the comment belongs to.
     *
     * @return BelongsTo<Post, self>
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the user that the comment belongs to.
     *
     * @return BelongsTo<User, self>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent comment.
     *
     * @return BelongsTo<Comment, self>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Get the child comments.
     *
     * @return HasMany<Comment>
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    /**
     * Get all approved replies.
     *
     * @return HasMany<Comment>
     */
    public function approvedReplies(): HasMany
    {
        return $this->replies()->where('is_approved', true);
    }

    /**
     * Check if comment has replies.
     *
     * @return bool
     */
    public function hasReplies(): bool
    {
        return $this->replies()->where('is_approved', true)->exists();
    }

    /**
     * Get all of the comment's likes.
     */
    public function likes()
    {
        return $this->morphMany(\App\Models\Like::class, 'likeable');
    }

    /**
     * Scope to get only top-level comments (no parent).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }
}
