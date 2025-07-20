<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;

class PopularPosts extends Component
{
    public $posts;

    public function mount()
    {
        $this->posts = Post::published()->with(['category', 'user', 'tags'])->orderByDesc('views')->take(6)->get();
    }

    public function render()
    {
        return view('livewire.popular-posts');
    }
}
