@props(['post'])
<div class="flex flex-col h-full overflow-hidden transition bg-white shadow-md rounded-xl group hover:shadow-lg">
    <!-- Post Image -->
    @if($post->image)
    <div class="relative">
        <img src="{{ $post->image }}" alt="{{ $post->title }}"
            class="object-cover w-full h-48 transition-transform duration-300 group-hover:scale-105" />
        @if($post->is_featured)
        <span
            class="absolute flex items-center px-3 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full top-3 right-3">
            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                </path>
            </svg>
            Featured
        </span>
        @endif
    </div>
    @endif

    <div class="flex flex-col flex-1 p-5 space-y-3">
        <!-- Category -->
        @if($post->category)
        <span
            class="inline-block px-3 py-1 mb-1 text-xs font-semibold text-indigo-800 bg-indigo-100 rounded-full max-w-fit">
            {{ $post->category->name }}
        </span>
        @endif

        <!-- Title -->
        <a href="{{ route('posts.show', $post->slug) }}"
            class="text-xl font-bold text-gray-900 transition hover:text-indigo-600 line-clamp-2">
            {{ $post->title }}
        </a>

        <!-- Excerpt -->
        <p class="text-sm text-gray-600 line-clamp-3">
            {{ Str::limit($post->excerpt, 120) }}
        </p>

        <!-- Tags -->
        @if($post->tags->count() > 0)
        <div class="flex flex-wrap gap-1">
            @foreach ($post->tags->take(3) as $tag)
            <span class="px-2 py-1 text-xs text-gray-700 bg-gray-100 rounded-md">#{{ $tag->name }}</span>
            @endforeach
            @if($post->tags->count() > 3)
            <span class="px-2 py-1 text-xs text-gray-500 bg-gray-100 rounded-md">
                +{{ $post->tags->count() - 3 }} more
            </span>
            @endif
        </div>
        @endif

        <!-- Meta Row -->
        <div class="flex items-center justify-between pt-4 mt-auto border-t border-gray-100">
            <div class="flex items-center gap-3 text-xs text-gray-500">
                @if($post->user)
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    {{ $post->user->name }}
                </span>
                @endif
                <span class="hidden sm:inline">&bull;</span>
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    {{ $post->created_at->format('M j, Y') }}
                </span>
                <span class="flex items-center text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-1 text-indigo-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    {{ $post->views }}
                </span>
            </div>
        </div>
        <!-- Like Button Block -->
        <div class="flex items-center justify-end pt-2 pb-1">
            @livewire('like-button', ['likeableType' => \App\Models\Post::class, 'likeableId' => $post->id],
            key('like-card-'.$post->id))
        </div>
    </div>
</div>
