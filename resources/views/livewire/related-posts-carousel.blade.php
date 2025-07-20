@php
$relatedPosts = $this->getRelatedPosts();
$visiblePosts = $this->getVisiblePosts();
$totalSlides = $this->getTotalSlides();
@endphp
<div>

    @if($relatedPosts->count() > 0)
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Related Posts</h3>
            <p class="card-subtitle">More from {{ $currentPost->category->name ?? 'this category' }}</p>
        </div>

        <div class="carousel-container">
            <!-- Carousel Container -->
            <div class="overflow-hidden">
                <div class="flex carousel-slide"
                    style="transform: translateX(-{{ $currentIndex * (100 / $itemsPerView) }}%)">
                    @foreach($visiblePosts as $post)
                    <div class="flex-shrink-0 w-full px-2 md:w-1/2 lg:w-1/3">
                        <div
                            class="overflow-hidden transition-shadow duration-200 bg-white border border-gray-200 rounded-lg hover:shadow-lg">
                            @if($post->image)
                            <div class="aspect-w-16 aspect-h-9">
                                <img src="{{ $post->image }}" alt="{{ $post->title }}" class="object-cover w-full h-48">
                            </div>
                            @endif

                            <div class="p-4">
                                <!-- Category -->
                                @if($post->category)
                                <div class="mb-2">
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">
                                        {{ $post->category->name }}
                                    </span>
                                </div>
                                @endif

                                <!-- Title -->
                                <h4 class="mb-2 text-lg font-semibold text-gray-900 line-clamp-2">
                                    <a href="{{ route('posts.show', $post) }}"
                                        class="transition-colors duration-200 hover:text-blue-600">
                                        {{ $post->title }}
                                    </a>
                                </h4>

                                <!-- Excerpt -->
                                @if($post->excerpt)
                                <p class="mb-3 text-sm text-gray-600 line-clamp-3">
                                    {{ $post->excerpt }}
                                </p>
                                @endif

                                <!-- Meta -->
                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <div class="flex items-center">
                                        @if($post->user)
                                        <span>{{ $post->user->name }}</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        {{ $post->created_at->format('M j, Y') }}
                                    </div>
                                </div>

                                <!-- Tags -->
                                @if($post->tags->count() > 0)
                                <div class="flex flex-wrap gap-1 mt-3">
                                    @foreach($post->tags->take(2) as $tag)
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded">
                                        #{{ $tag->name }}
                                    </span>
                                    @endforeach
                                    @if($post->tags->count() > 2)
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded">
                                        +{{ $post->tags->count() - 2 }}
                                    </span>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Navigation Buttons -->
            @if($totalSlides > 1)
            <!-- Previous Button -->
            <button wire:click="previous" @if($currentIndex===0) disabled @endif class="carousel-nav-button left-2">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>

            <!-- Next Button -->
            <button wire:click="next" @if($currentIndex>= $totalSlides - 1) disabled @endif
                class="carousel-nav-button right-2">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

            <!-- Dots Indicator -->
            <div class="carousel-dots">
                @for($i = 0; $i < $totalSlides; $i++) <button wire:click="goToSlide({{ $i }})"
                    class="carousel-dot {{ $i === $currentIndex ? 'active' : '' }}">
                    </button>
                    @endfor
            </div>
            @endif
        </div>

        <!-- View All Button -->
        @if($relatedPosts->count() > $itemsPerView)
        <div class="mt-4 text-center">
            <a href="{{ route('posts.index', ['category' => $currentPost->category->slug ?? '']) }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 transition-colors duration-200 border border-blue-200 rounded-md bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                View All {{ $currentPost->category->name ?? 'Related' }} Posts
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
        @endif
    </div>
    @endif
</div>
