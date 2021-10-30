<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Paginator::defaultView('pagination::default');

        view()->composer('partials.sidebar',function($view){
          
            $view->with('categories',Category::all());
        });
        
        view()->composer('partials.sidebar',function($view){
            $view->with('posts',Post::with('category','user')->latest()->limit(3)->get());
        });
        view()->composer('category.show',function($view){
            $view->with('posts',Post::with('category','user')->latest()->get());
        });

    }
}
