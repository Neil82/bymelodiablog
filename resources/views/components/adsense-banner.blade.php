@props(['slot' => 'auto', 'format' => 'auto', 'responsive' => true, 'class' => ''])

@php
    $adsenseClientId = App\Models\SiteSetting::get('adsense_client_id');
@endphp

@if($adsenseClientId)
    <div class="adsense-container {{ $class }}" data-ad-type="banner">
        <!-- Google AdSense Banner -->
        <ins class="adsbygoogle"
             style="display:{{ $responsive ? 'block' : 'inline-block' }};width:{{ $responsive ? '100%' : 'auto' }};height:auto;"
             data-ad-client="{{ $adsenseClientId }}"
             data-ad-slot="{{ $slot }}"
             @if($format !== 'auto') data-ad-format="{{ $format }}" @endif
             @if($responsive) data-full-width-responsive="true" @endif
             data-needs-init="true"></ins>
    </div>
@else
    <!-- AdSense Placeholder - Only visible to admins -->
    @auth
        <div class="adsense-placeholder bg-gray-100 dark:bg-gray-800 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-8 text-center {{ $class }}">
            <div class="text-gray-500 dark:text-gray-400">
                <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-medium mb-2">Espacio para Anuncio</h3>
                <p class="text-sm">Configura tu AdSense Client ID en el admin para mostrar anuncios aquí</p>
            </div>
        </div>
    @endauth
@endif