@php
    $adsenseClientId = App\Models\SiteSetting::get('adsense_client_id');
    $autoAdsEnabled = App\Models\SiteSetting::get('adsense_auto_ads', 'false') === 'true';
@endphp

@if($adsenseClientId && $autoAdsEnabled)
    <!-- Google AdSense Script (Auto ads initialized via GDPR consent) -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ $adsenseClientId }}"
            crossorigin="anonymous"></script>
@endif