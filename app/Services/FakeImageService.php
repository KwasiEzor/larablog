<?php

namespace App\Services;

class FakeImageService
{
    public static function generateUserImage(string $text): string
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($text) . '&background=random&color=fff';
    }

    public static function generateCategoryImage(string $text): string
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($text) . '&background=random&color=fff';
    }

    public static function generatePostImage(string $text): string
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($text) . '&background=random&color=fff';
    }
    public static function generateRealImageUrl(int $width = 600, int $height = 400): string
    {
        return 'https://picsum.photos/' . $width . '/' . $height . '?random=' . rand(1, 10000);
    }
}
