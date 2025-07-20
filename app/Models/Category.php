<?php

namespace App\Models;

use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Observers\CategoryObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

/**
 * Category Model
 *
 * Represents a blog category with hierarchical structure support.
 * Categories can have parent-child relationships and are associated with posts.
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string|null $image
 * @property bool $is_active
 * @property bool $is_featured
 * @property int|null $parent_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read Category|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Category> $children
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Post> $posts
 */

#[ObservedBy(CategoryObserver::class)]
class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory, Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['name', 'slug', 'description', 'image', 'is_active', 'is_featured', 'parent_id'];

    /**
     * Configure the sluggable behavior for the Category model.
     * Automatically generates a URL-friendly slug from the category name.
     *
     * @return array<string, array<string, string>>
     */
    public function sluggable(): array
    {
        return [
            'slug' => ['source' => 'name']
        ];
    }

    /**
     * The attributes that should be cast.
     * Converts boolean fields to proper boolean types.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    /**
     * Get the parent category of this category.
     * Returns null if this is a top-level category.
     *
     * @return BelongsTo<Category, self>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the child categories of this category.
     * Returns an empty collection if this category has no children.
     *
     * @return HasMany<Category, self>
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Get all posts that belong to this category.
     *
     * @return HasMany<Post, self>
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Scope a query to only include active categories.
     * Filters categories where is_active is true.
     *
     * @param Builder<Category> $query
     * @return Builder<Category>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }


    /**
     * Scope a query to only include parent categories.
     * Filters categories where parent_id is null.
     *
     * @param Builder<Category> $query
     * @return Builder<Category>
     */
    public function scopeParent(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope a query to only include child categories.
     * Filters categories where parent_id is not null.
     *
     * @param Builder<Category> $query
     * @return Builder<Category>
     */
    public function scopeChild(Builder $query): Builder
    {
        return $query->whereNotNull('parent_id');
    }

    /**
     * Scope a query to only include featured categories.
     * Filters categories where is_featured is true.
     *
     * @param Builder<Category> $query
     * @return Builder<Category>
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true)->active();
    }


    /**
     * Get the image URL for the category.
     * Returns a default image if no custom image is set.
     *
     * @return Attribute<string, string>
     */
    protected function image(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (!$value) {
                    return asset('images/default-category.jpg');
                }

                return str_starts_with($value, 'http')
                    ? $value
                    : Storage::url($value);
            }
        );
    }
}
