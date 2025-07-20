<div>
    <div class="space-y-6">
        <!-- Search and Filters Section -->
        <div class="card card-primary">
            <div class="card-header">
                <h2 class="card-title">Search & Filter Posts</h2>
                <p class="card-subtitle">Find the content you're looking for</p>
            </div>
            <div class="space-y-4">
                <!-- Search Bar -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <x-input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Search posts by title, content, or excerpt..." class="w-full pl-10" />
                </div>
                <!-- Filters Row -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                    <!-- Category Filter -->
                    <div>
                        <label for="category" class="block mb-1 text-sm font-medium text-gray-700">Category</label>
                        <select wire:model.live="category" id="category"
                            class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Categories</option>
                            @foreach($this->categories as $category)
                            <option value="{{ $category->slug }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Tag Filter -->
                    <div>
                        <label for="tag" class="block mb-1 text-sm font-medium text-gray-700">Tag</label>
                        <select wire:model.live="tag" id="tag"
                            class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Tags</option>
                            @foreach($this->tags as $tag)
                            <option value="{{ $tag->slug }}">{{ $tag->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Author Filter -->
                    <div>
                        <label for="author" class="block mb-1 text-sm font-medium text-gray-700">Author</label>
                        <select wire:model.live="author" id="author"
                            class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Authors</option>
                            @foreach($this->authors as $author)
                            <option value="{{ $author->id }}">{{ $author->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Sort Filter -->
                    <div>
                        <label for="sortBy" class="block mb-1 text-sm font-medium text-gray-700">Sort By</label>
                        <select wire:model.live="sortBy" id="sortBy"
                            class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="latest">Latest First</option>
                            <option value="oldest">Oldest First</option>
                            <option value="title">Title A-Z</option>
                            <option value="featured">Featured Only</option>
                        </select>
                    </div>
                </div>
                <!-- Active Filters and Clear Button -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <div class="flex items-center gap-2">
                        @if($search || $category || $tag || $author)
                        <span class="text-sm text-gray-600">Active filters:</span>
                        @if($search)
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                            Search: "{{ $search }}"
                        </span>
                        @endif
                        @if($category)
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Category: {{ $this->categories->firstWhere('slug', $category)?->name }}
                        </span>
                        @endif
                        @if($tag)
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            Tag: {{ $this->tags->firstWhere('slug', $tag)?->name }}
                        </span>
                        @endif
                        @if($author)
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                            Author: {{ $this->authors->firstWhere('id', $author)?->name }}
                        </span>
                        @endif
                        @endif
                    </div>
                    @if($search || $category || $tag || $author)
                    <button wire:click="clearFilters"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                        Clear Filters
                    </button>
                    @endif
                </div>
            </div>
        </div>
        <!-- Results Summary -->
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600">
                @if($this->posts()->total() > 0)
                Showing {{ $this->posts()->firstItem() }} to {{ $this->posts()->lastItem() }} of {{
                $this->posts()->total() }} posts
                @else
                No posts found
                @endif
            </div>
            <div class="flex items-center gap-2">
                <label for="perPage" class="text-sm text-gray-600">Show:</label>
                <select wire:model.live="perPage" id="perPage"
                    class="text-sm border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="6">6</option>
                    <option value="12">12</option>
                    <option value="24">24</option>
                    <option value="48">48</option>
                </select>
            </div>
        </div>
        <!-- Posts Grid -->
        @if($this->posts()->count() > 0)
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($this->posts() as $post)
            <x-posts.post-card :post="$post" />
            @endforeach
        </div>
        <!-- Pagination -->
        <div class="mt-8">
            {{ $this->posts()->links() }}
        </div>
        @else
        <!-- No Results -->
        <div class="py-12 text-center card">
            <div class="w-12 h-12 mx-auto text-gray-400">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
            </div>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No posts found</h3>
            <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter criteria.</p>
            <div class="mt-6">
                <button wire:click="clearFilters"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Clear all filters
                </button>
            </div>
        </div>
        @endif
    </div>
</div>
