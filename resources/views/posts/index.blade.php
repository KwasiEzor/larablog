<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold leading-tight text-gray-900">
                    {{ __('Blog Posts') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Discover our latest articles and insights
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <div class="text-sm text-gray-500">
                    <span class="font-medium">{{ $posts->count() }}</span> total posts
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <livewire:post-list />
        </div>
    </div>
</x-app-layout>
