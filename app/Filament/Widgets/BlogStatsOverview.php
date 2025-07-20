<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Comment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BlogStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Posts', Post::count())
                ->description('All blog posts')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),

            Stat::make('Published Posts', Post::where('is_published', true)->count())
                ->description('Publicly visible posts')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Total Users', User::count())
                ->description('Registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            Stat::make('Categories', Category::count())
                ->description('Blog categories')
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->color('warning'),

            Stat::make('Comments', Comment::count())
                ->description('All comments')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('gray'),

            Stat::make('Pending Comments', Comment::where('is_approved', false)->count())
                ->description('Awaiting approval')
                ->descriptionIcon('heroicon-m-clock')
                ->color('danger'),
        ];
    }
}
