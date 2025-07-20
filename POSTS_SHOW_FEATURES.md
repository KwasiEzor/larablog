# Posts Show Page Features

This document describes the new features added to the posts.show page, including nested comments and related posts carousel.

## Features Overview

### 1. Nested Comments System

The posts.show page now includes a comprehensive nested comments system built with Livewire that supports:

- **Top-level comments**: Users can leave comments on posts
- **Nested replies**: Users can reply to existing comments, creating threaded discussions
- **Real-time updates**: Comments and replies are added without page refresh
- **User authentication**: Only authenticated users can comment and reply
- **Moderation**: Comments can be approved/disapproved (currently auto-approved for demo)
- **User permissions**: Users can delete their own comments, admins can delete any comment

#### Database Changes

A new migration was added to support nested comments:
- `parent_id` field added to `comments` table
- Self-referencing foreign key constraint for comment replies

#### Livewire Component: `PostComments`

**Location**: `app/Livewire/PostComments.php`

**Key Methods**:
- `addComment()`: Creates a new top-level comment
- `addReply()`: Creates a reply to an existing comment
- `startReply()`: Shows the reply form for a specific comment
- `cancelReply()`: Hides the reply form
- `deleteComment()`: Deletes a comment (with permission check)

**Features**:
- Pagination for comments (10 per page)
- Validation for comment content (3-1000 chars for comments, 3-500 for replies)
- Flash messages for user feedback
- Responsive design with proper spacing and visual hierarchy

### 2. Related Posts Carousel

A beautiful carousel component displays related posts from the same category:

#### Livewire Component: `RelatedPostsCarousel`

**Location**: `app/Livewire/RelatedPostsCarousel.php`

**Key Methods**:
- `next()`: Navigate to next set of posts
- `previous()`: Navigate to previous set of posts
- `goToSlide()`: Jump to specific slide
- `getRelatedPosts()`: Fetch posts from same category
- `getVisiblePosts()`: Get current visible posts

**Features**:
- Shows 3 posts per view (responsive: 1 on mobile, 2 on tablet, 3 on desktop)
- Navigation arrows and dot indicators
- Smooth transitions and hover effects
- Displays post images, titles, excerpts, categories, tags, and meta info
- "View All" button linking to category-specific posts page

## Implementation Details

### Database Schema

```sql
-- Comments table with nested support
ALTER TABLE comments ADD COLUMN parent_id BIGINT UNSIGNED NULL;
ALTER TABLE comments ADD CONSTRAINT fk_comments_parent_id 
    FOREIGN KEY (parent_id) REFERENCES comments(id) ON DELETE CASCADE;
```

### Model Relationships

**Comment Model** (`app/Models/Comment.php`):
```php
public function parent(): BelongsTo
{
    return $this->belongsTo(Comment::class, 'parent_id');
}

public function replies(): HasMany
{
    return $this->hasMany(Comment::class, 'parent_id');
}

public function scopeTopLevel($query)
{
    return $query->whereNull('parent_id');
}
```

**Post Model** (`app/Models/Post.php`):
```php
public function comments(): HasMany
{
    return $this->hasMany(Comment::class);
}
```

### CSS Classes Added

New utility classes in `resources/css/app.css`:

```css
/* Carousel styles */
.carousel-container { /* ... */ }
.carousel-slide { /* ... */ }
.carousel-nav-button { /* ... */ }
.carousel-dots { /* ... */ }
.carousel-dot { /* ... */ }
.carousel-dot.active { /* ... */ }
```

### Views Structure

```
resources/views/
├── posts/
│   └── show.blade.php (updated with Livewire components)
└── livewire/
    ├── post-comments.blade.php (nested comments interface)
    └── related-posts-carousel.blade.php (carousel interface)
```

## Usage

### For Users

1. **Viewing Comments**: Comments are displayed below the post content
2. **Adding Comments**: Authenticated users can leave comments using the form
3. **Replying**: Click "Reply" on any comment to add a nested reply
4. **Navigation**: Use pagination to browse through all comments
5. **Related Posts**: Browse related posts using the carousel navigation

### For Developers

1. **Adding Comments**: Use the `PostComments` Livewire component
2. **Customizing Carousel**: Modify the `RelatedPostsCarousel` component
3. **Styling**: Update CSS classes in `resources/css/app.css`
4. **Database**: Run migrations to add nested comment support

## Security Features

- **Authentication Required**: Only logged-in users can comment/reply
- **Permission Checks**: Users can only delete their own comments
- **Admin Override**: Admins can delete any comment
- **Input Validation**: Comment content is validated and sanitized
- **CSRF Protection**: Livewire handles CSRF protection automatically

## Performance Considerations

- **Eager Loading**: Comments are loaded with user relationships
- **Pagination**: Comments are paginated to prevent performance issues
- **Caching**: Consider adding caching for related posts in production
- **Database Indexes**: Ensure proper indexes on `parent_id` and `post_id` columns

## Future Enhancements

Potential improvements for the future:

1. **Comment Moderation**: Admin approval workflow for comments
2. **Rich Text Editor**: WYSIWYG editor for comments
3. **Comment Notifications**: Email notifications for replies
4. **Comment Reactions**: Like/dislike functionality
5. **Comment Search**: Search within comments
6. **Auto-save**: Auto-save draft comments
7. **Comment Analytics**: Track comment engagement metrics 
