<?php

namespace App\Traits;

use App\Models\Like;

trait Likable
{
    /**
     * Get all of the model's likes.
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * Determine if the model is liked by a given user.
     */
    public function isLikedBy($user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    /**
     * Like the model for a given user.
     */
    public function like($user)
    {
        if (!$this->isLikedBy($user)) {
            $this->likes()->create(['user_id' => $user->id]);
        }
    }

    /**
     * Unlike the model for a given user.
     */
    public function unlike($user)
    {
        $this->likes()->where('user_id', $user->id)->delete();
    }
}
