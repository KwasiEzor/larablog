<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LikeButton extends Component
{
    public $likeableType;
    public $likeableId;
    public $isLiked = false;
    public $likesCount = 0;

    public function mount($likeableType, $likeableId)
    {
        $this->likeableType = $likeableType;
        $this->likeableId = $likeableId;
        $this->updateLikeState();
    }

    public function toggleLike()
    {
        $user = Auth::user();
        if (!$user) return;
        $likeable = ($this->likeableType)::find($this->likeableId);
        if (!$likeable) return;
        if ($likeable->isLikedBy($user)) {
            $likeable->unlike($user);
        } else {
            $likeable->like($user);
        }
        $this->updateLikeState();
    }

    public function updateLikeState()
    {
        $user = Auth::user();
        $likeable = ($this->likeableType)::find($this->likeableId);
        $this->isLiked = $user && $likeable ? $likeable->isLikedBy($user) : false;
        $this->likesCount = $likeable ? $likeable->likes()->count() : 0;
    }

    public function render()
    {
        return view('livewire.like-button', [
            'isLiked' => $this->isLiked,
            'likesCount' => $this->likesCount,
        ]);
    }
}
