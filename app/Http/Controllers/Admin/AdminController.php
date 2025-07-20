<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class AdminController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:manage settings');
    }

    /**
     * Redirect to Filament admin panel
     */
    public function index()
    {
        // Check if user has admin or author role
        if (Auth::check() && Auth::user()->hasRole(['admin', 'author'])) {
            return redirect('/admin');
        }

        // Redirect unauthorized users back to regular dashboard
        return redirect()->route('dashboard');
    }
}
