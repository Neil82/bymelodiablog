@extends('layouts.admin')

@section('title', 'Visitor Analytics')

@section('content')
<div class="space-y-6">
    <!-- Header with Period Selector -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Visitor Analytics</h2>
            <p class="text-gray-600 dark:text-gray-400">Detailed insights about your website visitors</p>
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

    <!-- Demographics Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Demographics</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">Visitor demographics and location data</p>
        </div>
        <div class="p-6">
            @if(isset($demographics['by_country']) && $demographics['by_country']->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($demographics['by_country'] as $demographic)
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $demographic->country_name ?? 'Unknown' }}
                                </span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $demographic->visitors }} visitors
                                </span>
                            </div>
                            <div class="mt-2 bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min(100, ($demographic->visitors / $demographics['by_country']->max('visitors')) * 100) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="text-gray-400 mb-2">
                        <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400">No demographic data available for this period</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Traffic Sources Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Traffic Sources</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">How visitors are finding your website</p>
        </div>
        <div class="p-6">
            @if($trafficSources->count() > 0)
                <div class="space-y-4">
                    @foreach($trafficSources as $source)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-blue-600 rounded-full"></div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ ucfirst($source->source ?? 'Direct') }}
                                </span>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $source->visitors }} visitors
                                </span>
                                <div class="w-24 bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min(100, ($source->visitors / $trafficSources->max('visitors')) * 100) }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="text-gray-400 mb-2">
                        <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400">No traffic source data available for this period</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Session Durations Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Session Durations</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">How long visitors stay on your website</p>
        </div>
        <div class="p-6">
            @if($sessionDurations->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($sessionDurations as $duration)
                        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600 mb-1">{{ $duration->sessions }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ $duration->duration_range }}</div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="text-gray-400 mb-2">
                        <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400">No session duration data available for this period</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection