@php
    $googleAnalyticsId = App\Models\SiteSetting::get('google_analytics');
@endphp

@if($googleAnalyticsId)
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $googleAnalyticsId }}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '{{ $googleAnalyticsId }}', {
    page_title: document.title,
    page_location: window.location.href
  });
</script>
@endif