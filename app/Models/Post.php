<?php

namespace App\Models;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Permet de d'associer directement lors de la création d'un post l'id du User au model parent
    public static function boot()
    {
        parent::boot();
            self::creating(function($post){
            $post->user()->associate(auth()->user()->id);
            $post->category()->associate(request()->category);
        });
        self::updating(function($post){
            $post->category()->associate(request()->category);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function getTitleAttribute($attribute)
    {
        return Str::title($attribute);
    }
}
