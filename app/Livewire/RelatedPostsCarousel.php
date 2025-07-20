<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;

class RelatedPostsCarousel extends Component
{
    public Post $currentPost;
    public int $currentIndex = 0;
    public int $itemsPerView = 3;

    public function mount(Post $post)
    {
        $this->currentPost = $post;
    }

    public function next()
    {
        $relatedPosts = $this->getRelatedPosts();
        $maxIndex = max(0, $relatedPosts->count() - $this->itemsPerView);
        $this->currentIndex = min($this->currentIndex + 1, $maxIndex);
    }

    public function previous()
    {
        $this->currentIndex = max(0, $this->currentIndex - 1);
    }

    public function goToSlide($index)
    {
        $relatedPosts = $this->getRelatedPosts();
        $maxIndex = max(0, $relatedPosts->count() - $this->itemsPerView);
        $this->currentIndex = max(0, min($index, $maxIndex));
    }

    public function getRelatedPosts()
    {
        return Post::published()
            ->where('id', '!=', $this->currentPost->id)
            ->where('category_id', $this->currentPost->category_id)
            ->with(['category', 'user', 'tags'])
            ->orderBy('created_at', 'desc')
            ->limit(12)
            ->get();
    }

    public function getVisiblePosts()
    {
        $relatedPosts = $this->getRelatedPosts();
        return $relatedPosts->slice($this->currentIndex, $this->itemsPerView);
    }

    public function getTotalSlides()
    {
        $relatedPosts = $this->getRelatedPosts();
        return max(1, ceil($relatedPosts->count() / $this->itemsPerView));
    }

    public function render()
    {
        return view('livewire.related-posts-carousel');
    }
}
