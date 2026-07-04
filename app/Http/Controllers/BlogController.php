<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Support\SeoMeta;
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

        $seo = SeoMeta::blogIndex(route('site.blog'));

        return view('site.blog', compact('posts', 'seo'));
    }

    /**
     * Display the specified blog post.
     */
    public function show(string $slug): View
    {
        $post = BlogPost::where('status', 'publish')
            ->where('slug', $slug)
            ->firstOrFail();

        $seo = SeoMeta::blog($post, route('site.blog.show', $post->slug));

        return view('site.blog-detail', compact('post', 'seo'));
    }
}
