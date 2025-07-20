<?php

namespace App\Models;

use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    /** @use HasFactory<\Database\Factories\TagFactory> */
    use HasFactory, Sluggable;

    protected $fillable = ['name', 'slug'];

    /**
     * Configure the sluggable behavior for the Tag model.
     * Automatically generates a URL-friendly slug from the tag name.
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
     * Get all posts that belong to this tag.
     *
     * @return BelongsToMany<Post, self>
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }
}
