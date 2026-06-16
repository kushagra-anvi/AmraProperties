@extends('layouts.site')

@section('title', 'Blog & News - Amra Property')
@section('meta_description', 'Read the latest updates, real estate trends, investment guides, and news from Amra Property.')

@section('content')
<div class="pt-32 pb-24 bg-amra-light">
    <div class="max-w-7xl mx-auto px-6">
        
        <!-- Page Header -->
        <div class="text-center mb-16">
            <p class="text-amra-primary font-bold uppercase tracking-[0.25em] text-xs mb-3">Insights & News</p>
            <h1 class="text-4xl md:text-5xl font-serif font-bold text-amra-dark">Our <span class="text-amra-primary italic">Blog</span></h1>
            <p class="text-gray-500 text-sm mt-3">Real estate trends, guides, and updates from Lucknow & Mumbai.</p>
        </div>

        <!-- Blog Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($posts as $post)
                <article class="group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100 flex flex-col justify-between">
                    <div>
                        <!-- Featured Image -->
                        <div class="relative h-56 overflow-hidden bg-slate-100">
                            @if($post->featured_image)
                                <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-102">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-300">
                                    <i data-lucide="image" class="w-10 h-10"></i>
                                </div>
                            @endif
                            <div class="absolute top-4 left-4">
                                <span class="bg-amra-dark/80 text-white text-[10px] font-medium px-2.5 py-1 rounded-md backdrop-blur-md">
                                    {{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}
                                </span>
                            </div>
                        </div>

                        <!-- Card Content -->
                        <div class="p-6">
                            <h3 class="text-xl font-serif font-bold text-amra-dark mb-3 line-clamp-2 group-hover:text-teal-600 transition-colors">
                                <a href="{{ route('site.blog.show', $post->slug) }}">
                                    {{ $post->title }}
                                </a>
                            </h3>
                            <p class="text-xs text-gray-500 leading-relaxed line-clamp-3 mb-4">
                                {{ Str::limit(strip_tags($post->content), 120) }}
                            </p>
                        </div>
                    </div>

                    <!-- Footer Link -->
                    <div class="px-6 pb-6 pt-3 border-t border-slate-50 flex items-center justify-between">
                        <span class="text-xs font-bold text-teal-600 uppercase tracking-wider">Read Full Article</span>
                        <a href="{{ route('site.blog.show', $post->slug) }}" class="w-8 h-8 rounded-lg bg-teal-500/10 hover:bg-teal-500 text-teal-600 hover:text-white flex items-center justify-center transition-all duration-300">
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-full py-20 text-center bg-white border border-gray-100 rounded-3xl max-w-xl mx-auto shadow-sm">
                    <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mb-4 mx-auto">
                        <i data-lucide="newspaper" class="w-8 h-8 text-amra-primary"></i>
                    </div>
                    <h3 class="text-xl font-serif font-bold text-amra-dark mb-2">No Articles Yet</h3>
                    <p class="text-gray-500 text-sm max-w-sm mx-auto">We are currently drafting our first articles. Check back soon!</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($posts->hasPages())
            <div class="mt-16 flex justify-center">
                {{ $posts->links() }}
            </div>
        @endif

    </div>
</div>
@endsection
