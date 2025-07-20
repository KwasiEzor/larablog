<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PostObserver
{
    /**
     * Handle the Post "creating" event.
     */
    public function creating(Post $post): void
    {
        // If a post is marked as featured but not published, automatically publish it
        if ($post->is_featured && !$post->is_published) {
            $post->is_published = true;
        }

        // Set default values if not provided
        if (!isset($post->is_published)) {
            $post->is_published = false;
        }

        if (!isset($post->is_featured)) {
            $post->is_featured = false;
        }
    }

    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        // Check if the image has been changed
        if ($post->wasChanged('image')) {
            // Get the old image path from the original values (before update)
            $oldImage = $post->getOriginal('image');
            $newImage = $post->getRawOriginal('image');

            // If the old image is a full URL, extract the path part
            if ($oldImage && str_starts_with($oldImage, '/storage/')) {
                $oldImage = str_replace('/storage/', '', $oldImage);
            }

            Log::info("PostObserver::updated - Post ID: {$post->id}");
            Log::info("Old image: " . ($oldImage ?: 'null'));
            Log::info("New image: " . ($newImage ?: 'null'));
            Log::info("Old image exists: " . ($oldImage ? (Storage::disk('public')->exists($oldImage) ? 'true' : 'false') : 'N/A'));

            // Delete the old image if it exists and is not the same as the new one
            if ($oldImage && $oldImage !== $newImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
                Log::info("Old image deleted: {$oldImage}");
            } else {
                Log::info("Old image not deleted - conditions not met");
            }
        } else {
            Log::info("PostObserver::updated - Image was not changed for Post ID: {$post->id}");
        }
    }

    /**
     * Handle the Post "deleted" event.
     * This fires when a post is soft deleted - we DON'T delete the image here
     * because the post might be restored later.
     */
    public function deleted(Post $post): void
    {
        // Do NOT delete the image on soft delete
        // The image should remain in storage in case the post is restored
    }

    /**
     * Handle the Post "restored" event.
     */
    public function restored(Post $post): void
    {
        // Post has been restored from soft delete
        // No action needed for images as they should still exist
    }

    /**
     * Handle the Post "force deleted" event.
     * This fires when a post is permanently deleted - NOW we delete the image.
     */
    public function forceDeleted(Post $post): void
    {
        // Delete the image only when the post is permanently deleted
        // Use the raw image path from the database
        $imagePath = $post->getRawOriginal('image');
        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }
    }
}
