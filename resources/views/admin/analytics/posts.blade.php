@extends('layouts.admin')

@section('title', 'Post Analytics')

@section('content')
<div class="space-y-6">
    <!-- Header with Period Selector -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Post Performance</h2>
            <p class="text-gray-600 dark:text-gray-400">Analyze how your content is performing</p>
        </div>
        
        <div class="flex items-center space-x-4">
            <form method="GET" class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Period:</label>
                <select name="period" onchange="this.form.submit()" class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    <option value="7" {{ $period == '7' ? 'selected' : '' }}>Last 7 days</option>
                    <option value="30" {{ $period == '30' ? 'selected' : '' }}>Last 30 days</option>
                    <option value="90" {{ $period == '90' ? 'selected' : '' }}>Last 90 days</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Top Posts Performance -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Top Performing Posts</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">Posts with the most views in the selected period</p>
        </div>
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @if($topPosts->count() > 0)
                @foreach($topPosts as $index => $post)
                    <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    {{ $index + 1 }}
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $post['post']->title }}
                                        </h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            Published {{ $post['post']->published_at ? $post['post']->published_at->format('M j, Y') : 'Draft' }}
                                        </p>
                                    </div>
                                    <div class="flex items-center space-x-6 ml-4">
                                        <div class="text-center">
                                            <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $post['total_views'] }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">Views</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $post['total_views'] }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">Unique</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ number_format(($post['avg_time'] ?? 0) / 60, 1) }}m</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">Avg Time</div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('blog.show', $post['post']->slug) }}" target="_blank" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                </svg>
                                            </a>
                                            <a href="{{ route('admin.posts.edit', $post['post']->id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="p-12 text-center">
                    <div class="text-gray-400 mb-4">
                        <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Post Data</h3>
                    <p class="text-gray-600 dark:text-gray-400">No post analytics data available for this period.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Posts Activity -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Activity</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">Latest posts and their current performance</p>
        </div>
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @if($engagementPosts->count() > 0)
                @foreach($engagementPosts as $post)
                    <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $post['post']->title }}
                                </h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    Published {{ $post['post']->published_at ? $post['post']->published_at->format('M j, Y') : 'Draft' }}
                                    • {{ $post['post']->category->name ?? 'Uncategorized' }}
                                </p>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="text-center">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $post['total_views'] ?? 0 }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Views</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $post['post']->comments_count ?? 0 }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Comments</div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $post['post']->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($post['post']->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="p-12 text-center">
                    <div class="text-gray-400 mb-4">
                        <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Engagement Data</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">No engagement data available for this period.</p>
                    <a href="{{ route('admin.posts.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Create New Post
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection