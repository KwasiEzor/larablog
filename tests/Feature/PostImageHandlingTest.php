<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostImageHandlingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /** @test */
    public function it_keeps_image_when_post_is_soft_deleted()
    {
        // Create a post with an image
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $image = UploadedFile::fake()->image('post-image.jpg');
        $imagePath = $image->store('posts', 'public');

        $post = Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'image' => $imagePath,
        ]);

        // Verify image exists
        $this->assertTrue(Storage::disk('public')->exists($imagePath));

        // Soft delete the post
        $post->delete();

        // Image should still exist because it's a soft delete
        $this->assertTrue(Storage::disk('public')->exists($imagePath));
        $this->assertSoftDeleted($post);
    }

    /** @test */
    public function it_deletes_image_when_post_is_force_deleted()
    {
        // Create a post with an image
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $image = UploadedFile::fake()->image('post-image.jpg');
        $imagePath = $image->store('posts', 'public');

        $post = Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'image' => $imagePath,
        ]);

        // Verify image exists
        $this->assertTrue(Storage::disk('public')->exists($imagePath));

        // Force delete the post
        $post->forceDelete();

        // Image should be deleted
        $this->assertFalse(Storage::disk('public')->exists($imagePath));
    }

    /** @test */
    public function it_restores_post_with_image_intact()
    {
        // Create a post with an image
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $image = UploadedFile::fake()->image('post-image.jpg');
        $imagePath = $image->store('posts', 'public');

        $post = Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'image' => $imagePath,
        ]);

        // Soft delete the post
        $post->delete();

        // Restore the post
        $post->restore();

        // Image should still exist and be accessible
        $this->assertTrue(Storage::disk('public')->exists($imagePath));
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'deleted_at' => null,
        ]);
    }

    /** @test */
    public function it_deletes_old_image_when_post_image_is_updated()
    {
        // Create a post with an initial image
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $oldImage = UploadedFile::fake()->image('old-post-image.jpg');
        $oldImagePath = $oldImage->store('posts', 'public');

        $post = Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'image' => $oldImagePath,
        ]);

        // Create a new image
        $newImage = UploadedFile::fake()->image('new-post-image.jpg');
        $newImagePath = $newImage->store('posts', 'public');

        // Update the post with new image
        $post->update(['image' => $newImagePath]);

        // Old image should be deleted
        $this->assertFalse(Storage::disk('public')->exists($oldImagePath));

        // New image should exist
        $this->assertTrue(Storage::disk('public')->exists($newImagePath));
    }

    /** @test */
    public function debug_image_path_storage()
    {
        // Create a post with an image
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $image = UploadedFile::fake()->image('post-image.jpg');
        $imagePath = $image->store('posts', 'public');

        $post = Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'image' => $imagePath,
        ]);

        // Debug: Check what's stored in the database
        $post->refresh();

        // Check raw database value
        $rawImage = $post->getRawOriginal('image');
        $this->assertEquals($imagePath, $rawImage);

        // Check transformed value
        $transformedImage = $post->image;
        $this->assertNotEquals($imagePath, $transformedImage);

        // Force delete should work with raw path
        $post->forceDelete();
        $this->assertFalse(Storage::disk('public')->exists($imagePath));
    }
}
