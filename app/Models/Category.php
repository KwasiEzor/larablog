<?php

namespace App\Models;

use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * Function that defines relation category has many posts
     * @see posts
     */


    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
