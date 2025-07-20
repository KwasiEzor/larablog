<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = [
        'user_id',
    ];

    /**
     * Get the owning likeable model.
     */
    public function likeable()
    {
        return $this->morphTo();
    }

    /**
     * Get the user that owns the like.
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
