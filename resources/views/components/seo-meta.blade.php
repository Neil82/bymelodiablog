@props([
    'title' => null,
    'description' => null,
    'keywords' => null,
    'image' => null,
    'url' => null,
    'type' => 'website',
    'article' => null
])

@php
    // Get default SEO settings
    $siteTitle = App\Models\SiteSetting::get('site_name', 'ByMelodia - Blog de Cultura Juvenil');
    $siteDescription = App\Models\SiteSetting::get('site_description', 'Descubre las últimas tendencias en música, lifestyle y cultura juvenil.');
    $siteKeywords = App\Models\SiteSetting::get('site_keywords', 'cultura juvenil, música, tendencias, lifestyle');
    $defaultOgImage = App\Models\SiteSetting::get('og_image');
    $twitterHandle = App\Models\SiteSetting::get('twitter_handle', '@bymelodia');
    $facebookAppId = App\Models\SiteSetting::get('facebook_app_id');
    
    // Build final values
    $finalTitle = $title ? $title . ' - ' . explode(' - ', $siteTitle)[0] : $siteTitle;
    $finalDescription = $description ?: $siteDescription;
    $finalKeywords = $keywords ?: $siteKeywords;
    $finalImage = $image ?: ($defaultOgImage ? asset('storage/' . $defaultOgImage) : asset('images/bymelodia_negro.png'));
    $finalUrl = $url ?: request()->url();
@endphp

<!-- Primary Meta Tags -->
<title>{{ $finalTitle }}</title>
<meta name="title" content="{{ $finalTitle }}">
<meta name="description" content="{{ $finalDescription }}">
<meta name="keywords" content="{{ $finalKeywords }}">
<meta name="robots" content="index, follow">
<meta name="language" content="Spanish">
<meta name="author" content="ByMelodia">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ $finalUrl }}">
<meta property="og:title" content="{{ $finalTitle }}">
<meta property="og:description" content="{{ $finalDescription }}">
<meta property="og:image" content="{{ $finalImage }}">
<meta property="og:site_name" content="ByMelodia">
<meta property="og:locale" content="es_ES">

@if($facebookAppId)
<meta property="fb:app_id" content="{{ $facebookAppId }}">
@endif

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ $finalUrl }}">
<meta property="twitter:title" content="{{ $finalTitle }}">
<meta property="twitter:description" content="{{ $finalDescription }}">
<meta property="twitter:image" content="{{ $finalImage }}">
<meta property="twitter:site" content="{{ $twitterHandle }}">

<!-- Article specific meta tags -->
@if($article)
<meta property="article:author" content="{{ $article['author'] ?? 'ByMelodia' }}">
<meta property="article:published_time" content="{{ $article['published_time'] ?? '' }}">
<meta property="article:modified_time" content="{{ $article['modified_time'] ?? '' }}">
<meta property="article:section" content="{{ $article['section'] ?? 'Cultura Juvenil' }}">
@if(isset($article['tags']))
    @foreach($article['tags'] as $tag)
<meta property="article:tag" content="{{ $tag }}">
    @endforeach
@endif
@endif

<!-- Additional SEO -->
<link rel="canonical" href="{{ $finalUrl }}">
<meta name="theme-color" content="#3B82F6">

<!-- JSON-LD Structured Data -->
@if($type === 'article' && $article)
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "Article",
    "name": "{{ $finalTitle }}",
    "description": "{{ $finalDescription }}",
    "url": "{{ $finalUrl }}",
    "image": "{{ $finalImage }}",
    "author": {
        "@@type": "Person",
        "name": "{{ $article['author'] ?? 'ByMelodia' }}"
    },
    "publisher": {
        "@@type": "Organization",
        "name": "ByMelodia",
        "logo": {
            "@@type": "ImageObject",
            "url": "{{ asset('images/bymelodia_negro.png') }}"
        }
    },
    "datePublished": "{{ $article['published_time'] ?? '' }}",
    "dateModified": "{{ $article['modified_time'] ?? '' }}",
    "mainEntityOfPage": {
        "@@type": "WebPage",
        "@@id": "{{ $finalUrl }}"
    }
}
</script>
@else
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "WebSite",
    "name": "{{ $finalTitle }}",
    "description": "{{ $finalDescription }}",
    "url": "{{ $finalUrl }}",
    "image": "{{ $finalImage }}",
    "publisher": {
        "@@type": "Organization",
        "name": "ByMelodia",
        "url": "{{ url('/') }}",
        "logo": {
            "@@type": "ImageObject",
            "url": "{{ asset('images/bymelodia_negro.png') }}"
        }
    },
    "potentialAction": {
        "@@type": "ReadAction",
        "target": "{{ $finalUrl }}"
    }
}
</script>
@endif