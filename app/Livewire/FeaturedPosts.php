<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;

class FeaturedPosts extends Component
{
    public $posts;

    public function mount()
    {
        $this->posts = Post::featured()->with(['category', 'user', 'tags'])->latest()->take(6)->get();
    }

    public function render()
    {
        return view('livewire.featured-posts');
    }
}
