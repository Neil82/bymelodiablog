<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">

    @foreach($posts as $post)
    <url>
        <loc>{{ route('blog.show', $post->slug) }}</loc>
        <lastmod>{{ $post->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
        
        @if($post->published_at && $post->published_at->isAfter(now()->subDays(2)))
        <!-- Google News Sitemap for recent articles -->
        <news:news>
            <news:publication>
                <news:name>ByMelodia</news:name>
                <news:language>es</news:language>
            </news:publication>
            <news:publication_date>{{ $post->published_at->toAtomString() }}</news:publication_date>
            <news:title>{{ $post->title }}</news:title>
            <news:keywords>{{ $post->category->name }}, cultura juvenil, tendencias</news:keywords>
        </news:news>
        @endif
    </url>
    @endforeach

</urlset>