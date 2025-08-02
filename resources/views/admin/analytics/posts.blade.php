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
                                            <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $post['unique_views'] }}</div>
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

    <!-- Geographic Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Countries by Post -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Geographic Interest</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Countries reading your posts</p>
            </div>
            <div class="p-6">
                @if(isset($postCountryData) && $postCountryData->count() > 0)
                    <div class="space-y-6">
                        @foreach($postCountryData->take(3) as $postId => $countries)
                            @php $post = $topPosts->firstWhere('post.id', $postId) @endphp
                            @if($post)
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-b-0">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">
                                        {{ Str::limit($post['post']->title, 50) }}
                                    </h4>
                                    <div class="space-y-2">
                                        @foreach($countries->take(4) as $country)
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-lg">
                                                        @php
                                                            $flags = [
                                                                'MX' => '🇲🇽', 'CO' => '🇨🇴', 'AR' => '🇦🇷', 'ES' => '🇪🇸', 
                                                                'US' => '🇺🇸', 'CL' => '🇨🇱', 'PE' => '🇵🇪', 'BR' => '🇧🇷',
                                                                'VE' => '🇻🇪', 'EC' => '🇪🇨', 'UY' => '🇺🇾', 'PY' => '🇵🇾'
                                                            ];
                                                            echo $flags[$country->country_code] ?? '🌍';
                                                        @endphp
                                                    </span>
                                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $country->country_name }}</span>
                                                </div>
                                                <div class="flex items-center space-x-3">
                                                    <span class="text-xs text-gray-500">{{ $country->unique_visitors }} visitors</span>
                                                    <div class="w-20 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min(($country->total_views / $post['total_views']) * 100, 100) }}%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="text-gray-400 mb-2">🌍</div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">No geographic data available yet</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Time Analytics -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Time on Page</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Reader engagement per post</p>
            </div>
            <div class="p-6">
                @if(isset($postTimeAnalytics) && $postTimeAnalytics->count() > 0)
                    <div class="space-y-4">
                        @foreach($postTimeAnalytics->take(5) as $postId => $timeData)
                            @php $post = $topPosts->firstWhere('post.id', $postId) @endphp
                            @if($post)
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-b-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ Str::limit($post['post']->title, 40) }}
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                {{ $timeData->total_sessions ?? $post['total_views'] }} reading sessions
                                            </p>
                                        </div>
                                        <div class="text-right ml-4">
                                            <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                                {{ gmdate('i:s', $timeData->avg_time_seconds ?? $post['avg_time']) }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">avg time</div>
                                            @if(isset($timeData->max_time_seconds))
                                                <div class="text-xs text-green-600 dark:text-green-400 mt-1">
                                                    Max: {{ gmdate('i:s', $timeData->max_time_seconds) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- Engagement indicator -->
                                    @php
                                        $avgSeconds = $timeData->avg_time_seconds ?? $post['avg_time'];
                                        $engagementLevel = $avgSeconds < 60 ? 'low' : ($avgSeconds < 180 ? 'medium' : 'high');
                                        $engagementColor = $engagementLevel === 'high' ? 'bg-green-500' : ($engagementLevel === 'medium' ? 'bg-yellow-500' : 'bg-red-500');
                                    @endphp
                                    <div class="mt-2 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1">
                                        <div class="{{ $engagementColor }} h-1 rounded-full" style="width: {{ min(($avgSeconds / 300) * 100, 100) }}%"></div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="text-gray-400 mb-2">⏱️</div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">No time analytics available yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Posts Activity -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Activity</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">Posts viewed in the last 24 hours</p>
        </div>
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @if(isset($recentActivity) && $recentActivity->count() > 0)
                @foreach($recentActivity as $activity)
                    <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $activity['post']->title }}
                                </h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    Last viewed {{ $activity['last_viewed']->diffForHumans() }}
                                    • {{ $activity['post']->category->name ?? 'Uncategorized' }}
                                </p>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="text-center">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $activity['views'] }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Views (24h)</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $activity['unique_views'] }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Unique (24h)</div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('blog.show', $activity['post']->slug) }}" target="_blank" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </a>
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
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Recent Activity</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">No posts have been viewed in the last 24 hours.</p>
                    <a href="{{ route('admin.posts.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Create New Post
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection