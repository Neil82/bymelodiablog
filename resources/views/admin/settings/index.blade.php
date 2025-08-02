@extends('layouts.admin')

@section('title', __('admin.settings.title'))

@section('content')
<div class="max-w-6xl mx-auto">
        
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl">
                        {{ __('admin.settings.title') }}
                    </h2>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        {{ __('admin.settings.description') }}
                    </p>
                </div>
                
                <!-- Language Selector -->
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('admin.posts.language') }}:</span>
                    @foreach($activeLanguages as $language)
                        @php
                            $hasTranslation = $language->code === 'es';
                            // Check if any setting has translation for this language
                            if ($language->code !== 'es') {
                                foreach($settings as $group) {
                                    foreach($group as $setting) {
                                        if (in_array($setting->type, ['text', 'textarea'])) {
                                            $translation = App\Models\SiteSettingTranslation::where('site_setting_id', $setting->id)
                                                ->where('language_id', $language->id)
                                                ->exists();
                                            if ($translation) {
                                                $hasTranslation = true;
                                                break 2;
                                            }
                                        }
                                    }
                                }
                            }
                            $isActive = $currentLanguage === $language->code;
                        @endphp
                        <a href="{{ route('admin.settings.index', ['lang' => $language->code]) }}"
                           class="px-3 py-1 text-sm rounded-full transition-colors {{ $isActive 
                               ? 'bg-blue-600 text-white' 
                               : ($hasTranslation 
                                   ? 'bg-green-100 text-green-800 hover:bg-green-200' 
                                   : 'bg-gray-100 text-gray-600 hover:bg-gray-200') }}">
                            {{ strtoupper($language->code) }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="language" value="{{ $currentLanguage }}">
            
            <div class="space-y-8">
                @foreach($groups as $groupKey => $groupTitle)
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ $groupTitle }}
                            </h3>
                        </div>
                        
                        <div class="p-6 space-y-6">
                            @if(isset($settings[$groupKey]))
                                @foreach($settings[$groupKey] as $key => $setting)
                                    <div>
                                        <label for="{{ $key }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            {{ $setting->label }}
                                            @if($currentLanguage !== 'es' && in_array($setting->type, ['text', 'textarea']))
                                                <span class="ml-2 px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                                    {{ strtoupper($currentLanguage) }} translation
                                                </span>
                                            @endif
                                            @if($setting->description)
                                                <span class="block text-xs text-gray-500 dark:text-gray-400 font-normal">
                                                    {{ $setting->description }}
                                                </span>
                                            @endif
                                        </label>
                                        
                                        @if($setting->type === 'text')
                                            @php
                                                $displayValue = old("settings.{$key}");
                                                if ($displayValue === null) {
                                                    $displayValue = $currentLanguage !== 'es' && isset($setting->translated_value) 
                                                        ? $setting->translated_value 
                                                        : $setting->value;
                                                }
                                                $displayValue = $displayValue ?? '';
                                            @endphp
                                            @if($currentLanguage !== 'es' && in_array($setting->type, ['text', 'textarea']))
                                                <input type="text" name="settings[{{ $key }}]" id="{{ $key }}" 
                                                       value="{{ $displayValue }}"
                                                       placeholder="{{ $setting->value ? 'Original: ' . Str::limit($setting->value, 50) : '' }}"
                                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            @else
                                                <input type="text" name="settings[{{ $key }}]" id="{{ $key }}" 
                                                       value="{{ $displayValue }}"
                                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            @endif
                                        
                                        @elseif($setting->type === 'textarea')
                                            @php
                                                $displayValue = old("settings.{$key}");
                                                if ($displayValue === null) {
                                                    $displayValue = $currentLanguage !== 'es' && isset($setting->translated_value) 
                                                        ? $setting->translated_value 
                                                        : $setting->value;
                                                }
                                                $displayValue = $displayValue ?? '';
                                            @endphp
                                            @if($currentLanguage !== 'es' && in_array($setting->type, ['text', 'textarea']))
                                                <textarea name="settings[{{ $key }}]" id="{{ $key }}" rows="4"
                                                          placeholder="{{ $setting->value ? 'Original: ' . Str::limit($setting->value, 100) : '' }}"
                                                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ $displayValue }}</textarea>
                                            @else
                                                <textarea name="settings[{{ $key }}]" id="{{ $key }}" rows="4"
                                                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ $displayValue }}</textarea>
                                            @endif
                                        
                                        @elseif($setting->type === 'boolean')
                                            <div class="flex items-center">
                                                <input type="checkbox" name="settings[{{ $key }}]" id="{{ $key }}" value="1"
                                                       {{ old("settings.{$key}", $setting->value) === 'true' ? 'checked' : '' }}
                                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                                <label for="{{ $key }}" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                                    {{ __('admin.general.activate') }}
                                                </label>
                                            </div>
                                        
                                        @elseif($setting->type === 'image')
                                            <div class="space-y-3">
                                                @if($setting->value)
                                                    <div class="flex items-start space-x-4">
                                                        <img src="{{ asset('storage/' . $setting->value) }}" 
                                                             alt="Imagen actual" 
                                                             class="h-20 w-32 object-cover rounded-lg">
                                                        <div class="flex-1">
                                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('admin.settings.current_image') }}</p>
                                                            <p class="text-xs text-gray-500 dark:text-gray-500">{{ $setting->value }}</p>
                                                        </div>
                                                    </div>
                                                @endif
                                                
                                                <input type="file" name="files[{{ $key }}]" id="file_{{ $key }}" accept="image/*"
                                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                                
                                                @if($key === 'banner_image_desktop')
                                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                        {{ __('admin.settings.desktop_image_hint') }}
                                                    </p>
                                                @elseif($key === 'banner_image_mobile')
                                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                        {{ __('admin.settings.mobile_image_hint') }}
                                                    </p>
                                                @elseif($key === 'about_banner_image_desktop')
                                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                        Imagen para la página Acerca de en escritorio (1920x600px recomendado)
                                                    </p>
                                                @elseif($key === 'about_banner_image_mobile')
                                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                        Imagen para la página Acerca de en móvil (800x400px recomendado)
                                                    </p>
                                                @elseif($key === 'og_image')
                                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                        {{ __('admin.settings.og_image_hint') }}
                                                    </p>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach

                <!-- SEO and AdSense Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- SEO Info -->
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800 dark:text-green-200">
                                    {{ __('admin.settings.seo_social_title') }}
                                </h3>
                                <div class="mt-2 text-sm text-green-700 dark:text-green-300">
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>{{ __('admin.settings.automatic_sitemap') }} <code>/sitemap.xml</code></li>
                                        <li>{{ __('admin.settings.open_graph_tags') }}</li>
                                        <li>{{ __('admin.settings.json_ld') }}</li>
                                        <li>{{ __('admin.settings.mobile_optimization') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- AdSense Info -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                                    {{ __('admin.settings.google_adsense') }}
                                </h3>
                                <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>{{ __('admin.settings.client_id') }} <code>ca-pub-xxxxxxxxxxxxxxxxx</code></li>
                                        <li>{{ __('admin.settings.auto_ads') }}</li>
                                        <li>{{ __('admin.settings.gdpr_compliant') }}</li>
                                        <li>{{ __('admin.settings.ads_after_cookies') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.posts.index') }}" 
                   class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">
                    {{ __('admin.general.cancel') }}
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    {{ __('admin.settings.save_settings') }}
                </button>
            </div>
        </form>

</div>
@endsection