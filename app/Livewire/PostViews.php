<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;

class PostViews extends Component
{
    public Post $post;

    public function mount(Post $post)
    {
        $this->post = $post;
        $this->incrementViews();
    }

    public function incrementViews()
    {
        $this->post->increment('views');
        $this->post->refresh();
    }

    public function render()
    {
        return view('livewire.post-views', [
            'views' => $this->post->views,
        ]);
    }
}
