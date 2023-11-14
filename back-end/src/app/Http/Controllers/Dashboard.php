<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Throwable;

class Dashboard extends Controller
{
    public function index()
    {
        try {
            $counts = [
                'users'             => User::count(),
                'published_posts'   => 0, // Post::where('published', true)->count()
                'unpublished_posts' => 0, // Post::where('published', false)->count()
            ];
            return view('pages.dashboard.dashboard', compact('counts'));
        } catch (Throwable $throwable) {
            $counts = [
                'users'             => 0,
                'published_posts'   => 0,
                'unpublished_posts' => 0,
            ];
            return view('pages.dashboard.dashboard', compact('counts'));
        }
    }
}
