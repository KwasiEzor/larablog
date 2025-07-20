<?php

namespace App\Models;

use App\Models\Tag;
use App\Models\User;
use App\Models\Category;
use App\Observers\PostObserver;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Traits\Likable;

#[ObservedBy(PostObserver::class)]
class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory, Sluggable, SoftDeletes, Likable;

    protected $fillable = ['title', 'excerpt', 'meta_title', 'meta_description', 'meta_keywords', 'slug', 'content', 'image', 'is_published', 'is_featured', 'category_id', 'user_id', 'views'];

    /**
     * Configure the sluggable behavior for the Post model.
     * Automatically generates a URL-friendly slug from the post title.
     *
     * @return array<string, array<string, string>>
     */
    public function sluggable(): array
    {
        return [
            'slug' => ['source' => 'title']
        ];
    }

    /**
     * Configure the casts for the Post model.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }
    /**
     * Get the category that the post belongs to.
     *
     * @return BelongsTo<Category, self>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the user that the post belongs to.
     *
     * @return BelongsTo<User, self>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tags that the post belongs to.
     *
     * @return BelongsToMany<Tag, self>
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Get the comments for the post.
     *
     * @return HasMany<Comment>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get all of the post's likes.
     */
    public function likes()
    {
        return $this->morphMany(\App\Models\Like::class, 'likeable');
    }

    /**
     * Scope a query to only include published posts.
     * Filters posts where is_published is true.
     *
     * @param Builder<Post> $query
     * @return Builder<Post>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope a query to only include featured posts.
     * Filters posts where is_featured is true and is_published is true.
     *
     * @param Builder<Post> $query
     * @return Builder<Post>
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true)->published();
    }

    /**
     * Scope a query to only include posts in a specific category.
     * Filters posts where category_id matches the given category ID.
     *
     * @param Builder<Post> $query
     * @param Category $category
     * @return Builder<Post>
     */
    public function scopeCategory(Builder $query, Category $category): Builder
    {
        return $query->where('category_id', $category->id);
    }

    /**
     * Scope a query to only include posts with a specific tag.
     * Filters posts that have a tag with the given tag ID.
     *
     * @param Builder<Post> $query
     * @param Tag $tag
     * @return Builder<Post>
     */
    public function scopeTag(Builder $query, Tag $tag): Builder
    {
        return $query->whereHas('tags', function ($query) use ($tag) {
            $query->where('tags.id', $tag->id);
        });
    }

    /**
     * Scope a query to search for posts by title.
     * Filters posts where the title contains the given search term.
     *
     * @param Builder<Post> $query
     * @param string $search
     * @return Builder<Post>
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where('title', 'like', '%' . $search . '%')
            ->orWhere('excerpt', 'like', '%' . $search . '%')
            ->orWhere('content', 'like', '%' . $search . '%');
    }

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn($value) => str_starts_with($value, 'http') ? $value : Storage::url($value) ?? asset('images/default-post.jpg'),
        );
    }
}
