<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class PostList extends Component
{
    use WithPagination;
    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'tag' => ['except' => ''],
        'author' => ['except' => ''],
        'sortBy' => ['except' => 'latest'],
    ];
    #[Url()]
    public $search = '';

    #[Url()]
    public $category = '';

    #[Url()]
    public $tag = '';

    #[Url()]
    public $author = '';

    #[Url()]
    public $sortBy = 'latest';

    #[Url()]
    public $perPage = 12;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingTag()
    {
        $this->resetPage();
    }

    public function updatingAuthor()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'category', 'tag', 'author']);
        $this->resetPage();
    }

    public function posts()
    {
        return Post::query()
            ->with(['category', 'user', 'tags'])
            ->when($this->search, function ($query) {
                $query->search($this->search);
            })
            ->when($this->category, function ($query) {
                $query->whereHas('category', function ($query) {
                    $query->where('slug', $this->category);
                });
            })
            ->when($this->tag, function ($query) {
                $query->whereHas('tags', function ($query) {
                    $query->where('slug', $this->tag);
                });
            })
            ->when($this->author, function ($query) {
                $query->whereHas('user', function ($query) {
                    $query->where('id', $this->author);
                });
            })
            ->when($this->sortBy === 'latest', function ($query) {
                $query->latest();
            })
            ->when($this->sortBy === 'oldest', function ($query) {
                $query->oldest();
            })
            ->when($this->sortBy === 'title', function ($query) {
                $query->orderBy('title');
            })
            ->when($this->sortBy === 'featured', function ($query) {
                $query->where('is_featured', true)->latest();
            })
            ->paginate($this->perPage);
    }

    public function getCategoriesProperty()
    {
        return Category::active()->orderBy('name')->get();
    }

    public function getTagsProperty()
    {
        return Tag::orderBy('name')->get();
    }

    public function getAuthorsProperty()
    {
        return User::whereHas('posts')->orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.post-list');
    }
}
