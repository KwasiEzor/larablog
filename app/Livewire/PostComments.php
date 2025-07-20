<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class PostComments extends Component
{
    use WithPagination, AuthorizesRequests;

    public Post $post;
    public string $content = '';
    public ?int $replyToId = null;
    public string $replyContent = '';
    public bool $showReplyForm = false;
    public int $perPage = 10;

    protected $rules = [
        'content' => 'required|min:3|max:1000',
        'replyContent' => 'required|min:3|max:500',
    ];

    protected $messages = [
        'content.required' => 'Please enter a comment.',
        'content.min' => 'Comment must be at least 3 characters.',
        'content.max' => 'Comment cannot exceed 1000 characters.',
        'replyContent.required' => 'Please enter a reply.',
        'replyContent.min' => 'Reply must be at least 3 characters.',
        'replyContent.max' => 'Reply cannot exceed 500 characters.',
    ];

    public function mount(Post $post)
    {
        $this->post = $post;
    }

    public function addComment()
    {
        $this->validate(['content' => 'required|min:3|max:1000']);

        $comment = Comment::create([
            'post_id' => $this->post->id,
            'user_id' => Auth::id(),
            'content' => $this->content,
            'is_approved' => true,
        ]);

        $this->content = '';

        $this->dispatch('comment-added', commentId: $comment->id);

        session()->flash('message', 'Comment added successfully!');
    }

    public function startReply($commentId)
    {
        $this->replyToId = $commentId;
        $this->showReplyForm = true;
        $this->replyContent = '';
    }

    public function cancelReply()
    {
        $this->replyToId = null;
        $this->showReplyForm = false;
        $this->replyContent = '';
    }

    public function addReply()
    {
        $this->validate(['replyContent' => 'required|min:3|max:500']);

        $parentComment = Comment::findOrFail($this->replyToId);

        $reply = Comment::create([
            'post_id' => $this->post->id,
            'user_id' => Auth::id(),
            'parent_id' => $this->replyToId,
            'content' => $this->replyContent,
            'is_approved' => true,
        ]);

        $this->cancelReply();
        $this->dispatch('reply-added', commentId: $reply->id);

        session()->flash('message', 'Reply added successfully!');
    }

    public function deleteComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);

        // Check if user can delete this comment
        if (Auth::id() === $comment->user_id || Auth::user()->hasRole('admin')) {
            $comment->delete();
            session()->flash('message', 'Comment deleted successfully!');
        } else {
            session()->flash('error', 'You are not authorized to delete this comment.');
        }
    }

    public function getCommentsProperty()
    {
        return $this->post->comments()
            ->with(['user', 'replies.user'])
            ->topLevel()
            ->where('is_approved', true)
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.post-comments');
    }
}
