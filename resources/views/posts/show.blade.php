<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <x-breadcrumb :post="$post" />
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl px-4 mx-auto sm:px-6 lg:px-8">
            <!-- Post Header -->
            <article class="card">
                @if($post->image)
                <div class="relative mb-6 -mx-6 -mt-6 overflow-hidden rounded-t-xl">
                    <img src="{{ $post->image }}" alt="{{ $post->title }}" class="object-cover w-full h-64 md:h-96" />
                    @if($post->is_featured)
                    <div class="absolute top-4 right-4">
                        <span
                            class="inline-flex items-center px-3 py-1 text-sm font-medium text-yellow-800 bg-yellow-100 rounded-full">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                            Featured
                        </span>
                    </div>
                    @endif
                </div>
                @endif

                <div class="space-y-4">
                    <!-- Category -->
                    @if($post->category)
                    <div class="flex items-center">
                        <span
                            class="inline-flex items-center px-3 py-1 text-sm font-medium text-indigo-800 bg-indigo-100 rounded-full">
                            {{ $post->category->name }}
                        </span>
                    </div>
                    @endif

                    <!-- Title -->
                    <h1 class="text-3xl font-bold leading-tight text-gray-900 md:text-4xl">
                        {{ $post->title }}
                    </h1>
                    <div class="flex items-center gap-4 mt-2">
                        @livewire('post-views', ['post' => $post], key('views-'.$post->id))
                        @livewire('like-button', ['likeableType' => \App\Models\Post::class, 'likeableId' => $post->id],
                        key('like-post-'.$post->id))
                    </div>

                    <!-- Meta Information -->
                    <div class="flex items-center pb-4 space-x-4 text-sm text-gray-500 border-b border-gray-100">
                        @if($post->user)
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ $post->user->name }}
                        </div>
                        @endif
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            {{ $post->created_at->format('F j, Y') }}
                        </div>
                    </div>

                    <!-- Tags -->
                    @if($post->tags->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach ($post->tags as $tag)
                        <span
                            class="inline-flex items-center px-3 py-1 text-sm font-medium text-gray-700 transition-colors duration-200 bg-gray-100 rounded-md hover:bg-gray-200">
                            #{{ $tag->name }}
                        </span>
                        @endforeach
                    </div>
                    @endif

                    <!-- Content -->
                    <div class="prose prose-lg text-justify max-w-none prose-indigo">
                        {!! $post->content !!}
                    </div>
                </div>
            </article>

            <!-- Comments Section -->
            <div class="mt-12">
                @livewire('post-comments', ['post' => $post])
            </div>

            <!-- Related Posts Carousel -->
            <div class="mt-12">
                @livewire('related-posts-carousel', ['post' => $post])
            </div>

            <!-- Back to Posts Button -->
            <div class="mt-8 text-center">
                <a wire:navigate href="{{ route('posts.index') }}"
                    class="inline-flex items-center px-6 py-3 text-base font-medium text-white transition-colors duration-200 bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to All Posts
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
