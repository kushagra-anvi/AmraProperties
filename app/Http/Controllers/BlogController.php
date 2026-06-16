<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    /**
     * Display a listing of the blog posts.
     */
    public function index(): View
    {
        $posts = BlogPost::where('status', 'publish')
            ->orderByDesc('published_at')
            ->paginate(6);

        return view('site.blog', compact('posts'));
    }

    /**
     * Display the specified blog post.
     */
    public function show(string $slug): View
    {
        $post = BlogPost::where('status', 'publish')
            ->where('slug', $slug)
            ->firstOrFail();

        return view('site.blog-detail', compact('post'));
    }
}
