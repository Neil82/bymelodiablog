@php
    $adsenseClientId = App\Models\SiteSetting::get('adsense_client_id');
    $autoAdsEnabled = App\Models\SiteSetting::get('adsense_auto_ads', 'false') === 'true';
@endphp

@if($adsenseClientId && $autoAdsEnabled)
    <!-- Google AdSense Auto Ads -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ $adsenseClientId }}"
            crossorigin="anonymous"></script>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({
            google_ad_client: "{{ $adsenseClientId }}",
            enable_page_level_ads: true
        });
    </script>
@endif