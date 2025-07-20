<div class="space-y-6">
    <!-- Flash Messages -->
    @if (session()->has('message'))
    <div class="p-4 mb-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50" role="alert">
        {{ session('message') }}
    </div>
    @endif

    @if (session()->has('error'))
    <div class="p-4 mb-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50" role="alert">
        {{ session('error') }}
    </div>
    @endif

    <!-- Comment Form -->
    @auth
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Leave a Comment</h3>
            <p class="card-subtitle">Share your thoughts on this post</p>
        </div>

        <form wire:submit="addComment" class="space-y-4">
            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 sr-only">Comment</label>
                <textarea wire:model="content" id="content" rows="4"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Write your comment here..." required></textarea>
                @error('content')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    Post Comment
                </button>
            </div>
        </form>
    </div>
    @else
    <div class="p-4 text-center rounded-lg bg-gray-50">
        <p class="text-gray-600">Please <a href="{{ route('login') }}"
                class="text-indigo-600 hover:text-indigo-800">login</a> to leave a comment.</p>
    </div>
    @endauth

    <!-- Comments List -->
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">
                Comments ({{ $this->comments->total() }})
            </h3>
        </div>

        @if($this->comments->count() > 0)
        <div class="space-y-6">
            @foreach($this->comments as $comment)
            <div class="comment-thread">
                <!-- Main Comment -->
                <div class="flex space-x-3">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center w-10 h-10 bg-gray-300 rounded-full">
                            <span class="text-sm font-medium text-gray-700">
                                {{ substr($comment->user->name, 0, 1) }}
                            </span>
                        </div>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="p-4 bg-white border border-gray-200 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm font-medium text-gray-900">{{ $comment->user->name }}</span>
                                    <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans()
                                        }}</span>
                                </div>

                                @auth
                                @if(auth()->id() === $comment->user_id || auth()->user()->hasRole('admin'))
                                <button wire:click="deleteComment({{ $comment->id }})"
                                    wire:confirm="Are you sure you want to delete this comment?"
                                    class="text-sm text-red-600 hover:text-red-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                                @endif
                                @endauth
                            </div>

                            <div class="mb-3 text-sm text-gray-700">
                                {{ $comment->content }}
                            </div>
                            <div class="flex items-center justify-end gap-2 mt-1">
                                @livewire('like-button', ['likeableType' => \App\Models\Comment::class, 'likeableId' =>
                                $comment->id], key('like-comment-'.$comment->id))
                            </div>

                            @auth
                            <button wire:click="startReply({{ $comment->id }})"
                                class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                Reply
                            </button>
                            @endauth
                        </div>

                        <!-- Reply Form -->
                        @if($replyToId === $comment->id && $showReplyForm)
                        <div class="mt-3 ml-6">
                            <form wire:submit="addReply" class="space-y-3">
                                <div>
                                    <textarea wire:model="replyContent" rows="3"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="Write your reply..." required></textarea>
                                    @error('replyContent')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex items-center space-x-2">
                                    <button type="submit"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Post Reply
                                    </button>
                                    <button type="button" wire:click="cancelReply"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                        @endif

                        <!-- Replies -->
                        @if($comment->hasReplies())
                        <div class="mt-4 space-y-3">
                            @foreach($comment->approvedReplies as $reply)
                            <div class="flex ml-6 space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center w-8 h-8 bg-gray-200 rounded-full">
                                        <span class="text-xs font-medium text-gray-600">
                                            {{ substr($reply->user->name, 0, 1) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="p-3 border border-gray-200 rounded-lg bg-gray-50">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center space-x-2">
                                                <span class="text-sm font-medium text-gray-900">{{ $reply->user->name
                                                    }}</span>
                                                <span class="text-xs text-gray-500">{{
                                                    $reply->created_at->diffForHumans() }}</span>
                                            </div>

                                            @auth
                                            @if(auth()->id() === $reply->user_id || auth()->user()->hasRole('admin'))
                                            <button wire:click="deleteComment({{ $reply->id }})"
                                                wire:confirm="Are you sure you want to delete this reply?"
                                                class="text-sm text-red-600 hover:text-red-800">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                            @endif
                                            @endauth
                                        </div>

                                        <div class="text-sm text-gray-700">
                                            {{ $reply->content }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $this->comments->links() }}
        </div>
        @else
        <div class="py-8 text-center">
            <div class="w-12 h-12 mx-auto mb-4 text-gray-400">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                    </path>
                </svg>
            </div>
            <h3 class="text-sm font-medium text-gray-900">No comments yet</h3>
            <p class="mt-1 text-sm text-gray-500">Be the first to share your thoughts!</p>
        </div>
        @endif
    </div>
</div>
