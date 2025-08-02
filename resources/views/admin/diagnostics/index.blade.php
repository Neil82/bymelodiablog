@extends('layouts.admin')

@section('title', 'Database Diagnostics')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Database Diagnostics</h2>
            <p class="text-gray-600 dark:text-gray-400">Complete overview of production database</p>
        </div>
        <div class="text-sm text-gray-500 dark:text-gray-400">
            Generated: {{ now()->format('Y-m-d H:i:s T') }}
        </div>
    </div>

    <!-- Database Stats -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Database Overview</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $diagnostics['database_stats']['posts_total'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Posts</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $diagnostics['database_stats']['posts_with_views'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Posts with Views</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-600">{{ $diagnostics['database_stats']['total_views'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Views</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-orange-600">{{ $diagnostics['database_stats']['user_sessions_total'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">User Sessions</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-red-600">{{ $diagnostics['database_stats']['tracking_events_total'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Tracking Events</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-indigo-600">{{ $diagnostics['database_stats']['site_settings_total'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Site Settings</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Posts Data -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Posts Data</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- All Posts -->
                <div>
                    <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">All Posts (Ordered by Views)</h4>
                    <div class="space-y-2 max-h-64 overflow-y-auto">
                        @foreach($diagnostics['posts_data']['all_posts'] as $post)
                            <div class="flex justify-between items-start p-3 bg-gray-50 dark:bg-gray-700 rounded">
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $post['title'] }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $post['id'] }} | Published: {{ $post['published_at'] ?? 'Draft' }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold text-blue-600">{{ $post['views'] }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">views</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Posts by Views Range -->
                <div>
                    <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Posts by View Range</h4>
                    <div class="space-y-2">
                        @foreach($diagnostics['posts_data']['posts_by_views'] as $range)
                            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded">
                                <span class="text-sm text-gray-900 dark:text-white">{{ $range->view_range }}</span>
                                <span class="text-sm font-bold text-blue-600">{{ $range->post_count }} posts</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Sessions Data -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">User Sessions Data</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Session Stats -->
                <div>
                    <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Session Statistics</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>Total Sessions:</span>
                            <span class="font-bold">{{ $diagnostics['user_sessions_data']['total_sessions'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Sessions with Country:</span>
                            <span class="font-bold">{{ $diagnostics['user_sessions_data']['sessions_with_country'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Unique Countries:</span>
                            <span class="font-bold">{{ $diagnostics['user_sessions_data']['unique_countries'] }}</span>
                        </div>
                    </div>
                    
                    <h5 class="text-sm font-medium text-gray-900 dark:text-white mt-6 mb-3">Countries Breakdown</h5>
                    <div class="space-y-1 max-h-32 overflow-y-auto">
                        @foreach($diagnostics['user_sessions_data']['countries_breakdown'] as $country)
                            <div class="flex justify-between text-sm">
                                <span>{{ $country->country_code }} {{ $country->country_name }}</span>
                                <span class="font-bold">{{ $country->session_count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recent Sessions -->
                <div>
                    <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Recent Sessions (Last 10)</h4>
                    <div class="space-y-2 max-h-64 overflow-y-auto">
                        @foreach($diagnostics['user_sessions_data']['recent_sessions'] as $session)
                            <div class="p-2 bg-gray-50 dark:bg-gray-700 rounded text-xs">
                                <div class="flex justify-between">
                                    <span class="font-medium">{{ $session['country_name'] ?? 'Unknown' }} ({{ $session['country_code'] ?? '--' }})</span>
                                    <span>{{ $session['page_views'] }} views</span>
                                </div>
                                <div class="text-gray-500 dark:text-gray-400">
                                    {{ $session['device_type'] }} | {{ $session['browser'] }}
                                </div>
                                <div class="text-gray-500 dark:text-gray-400">
                                    {{ $session['started_at'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tracking Events Data -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tracking Events Data</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Event Stats -->
                <div>
                    <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Event Statistics</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>Total Events:</span>
                            <span class="font-bold">{{ $diagnostics['tracking_events_data']['total_events'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Events with Post ID:</span>
                            <span class="font-bold">{{ $diagnostics['tracking_events_data']['events_with_post_id'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Events with Time Data:</span>
                            <span class="font-bold text-red-600">{{ $diagnostics['tracking_events_data']['events_with_time_data'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Events with >30s Time:</span>
                            <span class="font-bold text-red-600">{{ $diagnostics['tracking_events_data']['events_with_meaningful_time'] }}</span>
                        </div>
                    </div>
                    
                    <h5 class="text-sm font-medium text-gray-900 dark:text-white mt-6 mb-3">Events by Type</h5>
                    <div class="space-y-1">
                        @foreach($diagnostics['tracking_events_data']['events_by_type'] as $type)
                            <div class="flex justify-between text-sm">
                                <span>{{ $type->event_type }}</span>
                                <span class="font-bold">{{ $type->count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recent Events -->
                <div>
                    <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Recent Events (Last 10)</h4>
                    <div class="space-y-2 max-h-64 overflow-y-auto">
                        @foreach($diagnostics['tracking_events_data']['recent_events'] as $event)
                            <div class="p-2 bg-gray-50 dark:bg-gray-700 rounded text-xs">
                                <div class="flex justify-between">
                                    <span class="font-medium">{{ $event['event_type'] }}</span>
                                    <span>Post: {{ $event['post_id'] ?? 'N/A' }}</span>
                                </div>
                                <div class="text-gray-500 dark:text-gray-400">
                                    Time: {{ $event['time_on_page'] ? $event['time_on_page'] . 's' : 'No time data' }}
                                </div>
                                <div class="text-gray-500 dark:text-gray-400">
                                    {{ $event['event_time'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Query Results -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Analytics Query Results</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">Testing the exact queries used in analytics dashboard</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Geographic Query Results -->
                <div>
                    <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">
                        Geographic Query Results ({{ $diagnostics['analytics_queries']['geographic_results_count'] }} results)
                    </h4>
                    @if($diagnostics['analytics_queries']['geographic_results_count'] > 0)
                        <div class="space-y-2 max-h-48 overflow-y-auto">
                            @foreach($diagnostics['analytics_queries']['geographic_results'] as $result)
                                <div class="p-2 bg-green-50 dark:bg-green-900 rounded text-xs">
                                    <div class="font-medium">{{ $result->post_title }}</div>
                                    <div>{{ $result->country_name }} ({{ $result->country_code }}): {{ $result->unique_visitors }} visitors</div>
                                    <div>Avg time: {{ round($result->avg_time_on_page) }}s</div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-4 bg-red-50 dark:bg-red-900 rounded text-center">
                            <div class="text-red-600 dark:text-red-400 font-medium">No geographic data found</div>
                            <div class="text-sm text-red-500 dark:text-red-300">No tracking events with time_on_page > 30s</div>
                        </div>
                    @endif
                </div>

                <!-- Time Query Results -->
                <div>
                    <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">
                        Time Analytics Query Results ({{ $diagnostics['analytics_queries']['time_results_count'] }} results)
                    </h4>
                    @if($diagnostics['analytics_queries']['time_results_count'] > 0)
                        <div class="space-y-2 max-h-48 overflow-y-auto">
                            @foreach($diagnostics['analytics_queries']['time_results'] as $result)
                                <div class="p-2 bg-green-50 dark:bg-green-900 rounded text-xs">
                                    <div class="font-medium">{{ $result->post_title }}</div>
                                    <div>Avg: {{ round($result->avg_time_seconds) }}s | Max: {{ round($result->max_time_seconds) }}s</div>
                                    <div>Sessions: {{ $result->total_sessions }}</div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-4 bg-red-50 dark:bg-red-900 rounded text-center">
                            <div class="text-red-600 dark:text-red-400 font-medium">No time analytics data found</div>
                            <div class="text-sm text-red-500 dark:text-red-300">No tracking events with time_on_page data</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- System Info -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">System Information</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                <div><strong>PHP Version:</strong> {{ $diagnostics['system_info']['php_version'] }}</div>
                <div><strong>Laravel:</strong> {{ $diagnostics['system_info']['laravel_version'] }}</div>
                <div><strong>Database:</strong> {{ $diagnostics['system_info']['database_connection'] }}</div>
                <div><strong>Environment:</strong> {{ $diagnostics['system_info']['environment'] }}</div>
                <div><strong>Timezone:</strong> {{ $diagnostics['system_info']['timezone'] }}</div>
                <div><strong>Current Time:</strong> {{ $diagnostics['system_info']['current_time'] }}</div>
            </div>
        </div>
    </div>
</div>
@endsection