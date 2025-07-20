<div class="mb-10">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold text-gray-900">Featured Posts</h2>
        <a href="{{ route('posts.index') }}" class="text-indigo-600 hover:underline">View All</a>
    </div>
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($posts as $post)
        <x-posts.post-card :post="$post" />
        @endforeach
    </div>
    @if($posts->isEmpty())
    <div class="py-8 text-center text-gray-500">No featured posts found.</div>
    @endif
</div>
