<div class="flex items-center gap-2">
    <button wire:click="toggleLike" class="focus:outline-none stroke-indigo-500">
        <x-fontisto-like class="w-5 h-5 stroke-2 {{ $isLiked ? 'fill-indigo-500' : 'fill-none' }}" />
    </button>
    <p class="flex items-center gap-1">
        <span class="text-sm text-gray-600">Likes</span>
        <span class="flex items-center justify-center text-sm text-indigo-600">{{ $likesCount }}</span>
    </p>
</div>
