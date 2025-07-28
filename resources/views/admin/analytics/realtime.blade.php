@extends('layouts.admin')

@section('title', 'Real-time Analytics')

@push('scripts')
<script>
// Auto-refresh every 30 seconds
setInterval(function() {
    location.reload();
}, 30000);
</script>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Real-time Analytics</h2>
            <p class="text-gray-600 dark:text-gray-400">Live activity on your website (auto-refreshes every 30 seconds)</p>
        </div>
        
        <div class="flex items-center space-x-2">
            <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Live</span>
        </div>
    </div>

    <!-- Real-time Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Active Users -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Sessions</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $activeSessions }}</p>
                </div>
            </div>
        </div>

        <!-- Pages Views (Last Hour) -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Pages</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $activePages->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Live Sessions -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Recent Events</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $recentEvents->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Active Sessions -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Current Visitor Locations</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">Geographic distribution of active visitors</p>
        </div>
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @if($currentLocations->count() > 0)
                @foreach($currentLocations as $location)
                    <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                <div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $location->country_name ?? 'Unknown Location' }}
                                        </span>
                                        @if($location->city)
                                            <span class="text-sm text-gray-500 dark:text-gray-400">• {{ $location->city }}</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-4 mt-1">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $location->visitors }} visitor{{ $location->visitors != 1 ? 's' : '' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="text-center">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $location->visitors }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Visitors</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="p-12 text-center">
                    <div class="text-gray-400 mb-4">
                        <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Active Visitors</h3>
                    <p class="text-gray-600 dark:text-gray-400">No visitors are currently browsing your website.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Activity</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">Latest actions taken by visitors</p>
        </div>
        <div class="divide-y divide-gray-200 dark:divide-gray-700 max-h-96 overflow-y-auto">
            @if($recentEvents->count() > 0)
                @foreach($recentEvents as $event)
                    <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                <div>
                                    <span class="text-sm text-gray-900 dark:text-white">
                                        {{ ucfirst(str_replace('_', ' ', $event->event_type)) }}
                                    </span>
                                    @if($event->page_url)
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            on {{ $event->page_url }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $event->event_time->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="p-12 text-center">
                    <div class="text-gray-400 mb-4">
                        <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Recent Activity</h3>
                    <p class="text-gray-600 dark:text-gray-400">No recent visitor activity to display.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection