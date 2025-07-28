@extends('layouts.admin')

@section('title', 'Analytics Overview')

@section('content')
<div class="space-y-6">
    <!-- Header with Period Selector -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Analytics Overview</h2>
            <p class="text-gray-600 dark:text-gray-400">Track your website performance and visitor insights</p>
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

    <!-- Key Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Visitors -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Visitors</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['total_visitors']) }}</p>
                </div>
            </div>
        </div>

        <!-- Unique Visitors -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Unique Visitors</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['unique_visitors']) }}</p>
                </div>
            </div>
        </div>

        <!-- Page Views -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Page Views</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['total_page_views']) }}</p>
                </div>
            </div>
        </div>

        <!-- Avg Session Duration -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-2 bg-orange-100 dark:bg-orange-900 rounded-lg">
                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Avg Session</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ gmdate('i:s', $stats['avg_session_duration'] ?? 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Visitor Trends Chart -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Visitor Trends</h3>
            <div class="relative" style="height: 300px;">
                <canvas id="visitorTrendsChart"></canvas>
            </div>
        </div>

        <!-- Device Breakdown -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Device Breakdown</h3>
            <div class="relative" style="height: 300px;">
                <canvas id="deviceChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Pages -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top Pages</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="text-left py-2 text-sm font-medium text-gray-600 dark:text-gray-400">Page</th>
                            <th class="text-right py-2 text-sm font-medium text-gray-600 dark:text-gray-400">Views</th>
                        </tr>
                    </thead>
                    <tbody class="space-y-2">
                        @forelse($topPages as $page)
                        <tr class="border-b border-gray-100 dark:border-gray-700">
                            <td class="py-2">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $page->page_title ?: 'Untitled' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $page->url }}</p>
                            </td>
                            <td class="py-2 text-right text-sm text-gray-900 dark:text-white">{{ number_format($page->views) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="py-4 text-center text-gray-500 dark:text-gray-400">No data available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Countries -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top Countries</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="text-left py-2 text-sm font-medium text-gray-600 dark:text-gray-400">Country</th>
                            <th class="text-right py-2 text-sm font-medium text-gray-600 dark:text-gray-400">Visitors</th>
                        </tr>
                    </thead>
                    <tbody class="space-y-2">
                        @forelse($countryStats as $country)
                        <tr class="border-b border-gray-100 dark:border-gray-700">
                            <td class="py-2">
                                <div class="flex items-center space-x-2">
                                    <span class="text-lg">{{ country_flag($country->country_code) }}</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $country->country_name }}</span>
                                </div>
                            </td>
                            <td class="py-2 text-right text-sm text-gray-900 dark:text-white">{{ number_format($country->count) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="py-4 text-center text-gray-500 dark:text-gray-400">No data available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Top Posts -->
    @if($topPosts->count() > 0)
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top Performing Posts</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="text-left py-2 text-sm font-medium text-gray-600 dark:text-gray-400">Post</th>
                        <th class="text-right py-2 text-sm font-medium text-gray-600 dark:text-gray-400">Views</th>
                        <th class="text-right py-2 text-sm font-medium text-gray-600 dark:text-gray-400">Total Time</th>
                    </tr>
                </thead>
                <tbody class="space-y-2">
                    @foreach($topPosts as $postData)
                    @if($postData->post)
                    <tr class="border-b border-gray-100 dark:border-gray-700">
                        <td class="py-2">
                            <div class="flex items-center space-x-3">
                                @if($postData->post->featured_image)
                                <img src="{{ asset('storage/' . $postData->post->featured_image) }}" alt="" class="w-10 h-10 rounded object-cover">
                                @else
                                <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                @endif
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $postData->post->title }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $postData->post->category->name ?? 'Uncategorized' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-2 text-right text-sm text-gray-900 dark:text-white">{{ number_format($postData->total_views) }}</td>
                        <td class="py-2 text-right text-sm text-gray-900 dark:text-white">{{ gmdate('H:i:s', $postData->total_time) }}</td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Visitor Trends Chart
    const visitorCtx = document.getElementById('visitorTrendsChart').getContext('2d');
    const visitorData = @json($visitorTrends);
    
    new Chart(visitorCtx, {
        type: 'line',
        data: {
            labels: visitorData.map(d => new Date(d.date).toLocaleDateString()),
            datasets: [
                {
                    label: 'Total Visitors',
                    data: visitorData.map(d => d.visitors),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.1
                },
                {
                    label: 'Unique Visitors',
                    data: visitorData.map(d => d.unique_visitors),
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
            },
            plugins: {
                legend: {
                    labels: {
                        color: document.documentElement.classList.contains('dark') ? '#E5E7EB' : '#374151'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: document.documentElement.classList.contains('dark') ? '#E5E7EB' : '#374151'
                    },
                    grid: {
                        color: document.documentElement.classList.contains('dark') ? '#374151' : '#E5E7EB'
                    }
                },
                x: {
                    ticks: {
                        color: document.documentElement.classList.contains('dark') ? '#E5E7EB' : '#374151'
                    },
                    grid: {
                        color: document.documentElement.classList.contains('dark') ? '#374151' : '#E5E7EB'
                    }
                }
            }
        }
    });

    // Device Chart
    const deviceCtx = document.getElementById('deviceChart').getContext('2d');
    const deviceData = @json($deviceStats);
    
    new Chart(deviceCtx, {
        type: 'doughnut',
        data: {
            labels: deviceData.map(d => d.device_type || 'Unknown'),
            datasets: [{
                data: deviceData.map(d => d.count),
                backgroundColor: [
                    'rgb(59, 130, 246)',   // Blue
                    'rgb(16, 185, 129)',   // Green
                    'rgb(245, 158, 11)',   // Yellow
                    'rgb(239, 68, 68)',    // Red
                    'rgb(139, 92, 246)'    // Purple
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: document.documentElement.classList.contains('dark') ? '#E5E7EB' : '#374151',
                        padding: 20
                    }
                }
            }
        }
    });
</script>
@endpush

@php
    // Helper function for country flags
    function country_flag($countryCode) {
        if (!$countryCode) return '🌍';
        
        $flags = [
            'US' => '🇺🇸', 'GB' => '🇬🇧', 'CA' => '🇨🇦', 'AU' => '🇦🇺',
            'DE' => '🇩🇪', 'FR' => '🇫🇷', 'ES' => '🇪🇸', 'IT' => '🇮🇹',
            'BR' => '🇧🇷', 'MX' => '🇲🇽', 'AR' => '🇦🇷', 'CO' => '🇨🇴',
            'IN' => '🇮🇳', 'CN' => '🇨🇳', 'JP' => '🇯🇵', 'KR' => '🇰🇷'
        ];
        
        return $flags[$countryCode] ?? '🌍';
    }
@endphp
@endsection