@extends('layouts.site')

@section('title', $post->title . ' - Amra Property')
@section('meta_description', Str::limit(strip_tags($post->content), 150))

@section('content')
<div class="pt-32 pb-24 bg-amra-light">
    <div class="max-w-4xl mx-auto px-6">
        
        <!-- Back Navigation -->
        <div class="mb-8">
            <a href="{{ route('site.blog') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-teal-600 hover:text-teal-700 transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Blog
            </a>
        </div>

        <article class="bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 p-6 md:p-10">
            <!-- Article Header -->
            <header class="mb-8">
                <div class="flex items-center gap-2 mb-4">
                    <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2.5 py-1 rounded-md">
                        Published on {{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}
                    </span>
                </div>
                <h1 class="text-3xl md:text-4xl font-serif font-bold text-amra-dark leading-snug">
                    {{ $post->title }}
                </h1>
            </header>

            <!-- Featured Image -->
            @if($post->featured_image)
                <div class="relative h-[300px] md:h-[450px] w-full rounded-2xl overflow-hidden mb-10 bg-slate-50 shadow-inner">
                    <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                </div>
            @endif

            <!-- Article Body Content -->
            <div class="blog-post-content text-slate-700 text-sm md:text-base space-y-6">
                {!! $post->content !!}
            </div>

        </article>

    </div>
</div>

<style>
.blog-post-content h1, .blog-post-content h2, .blog-post-content h3, .blog-post-content h4 {
    font-family: 'Outfit', sans-serif;
    color: #0F172A;
    font-weight: 700;
    margin-top: 2rem;
    margin-bottom: 1rem;
    line-height: 1.3;
}
.blog-post-content h1 { font-size: 1.75rem; }
.blog-post-content h2 { font-size: 1.5rem; }
.blog-post-content h3 { font-size: 1.25rem; }
.blog-post-content h4 { font-size: 1.1rem; }

.blog-post-content p {
    margin-bottom: 1.5rem;
    line-height: 1.75;
}
.blog-post-content ul {
    list-style-type: disc;
    padding-left: 1.5rem;
    margin-bottom: 1.5rem;
}
.blog-post-content ol {
    list-style-type: decimal;
    padding-left: 1.5rem;
    margin-bottom: 1.5rem;
}
.blog-post-content li {
    margin-bottom: 0.5rem;
}
.blog-post-content strong {
    color: #0F172A;
    font-weight: 700;
}
.blog-post-content a {
    color: #0bc1b2;
    text-decoration: underline;
    font-weight: 600;
}
.blog-post-content a:hover {
    color: #09a296;
}
.blog-post-content blockquote {
    border-left: 4px solid #0bc1b2;
    padding-left: 1rem;
    font-style: italic;
    color: #475569;
    margin: 1.5rem 0;
}
</style>
@endsection
