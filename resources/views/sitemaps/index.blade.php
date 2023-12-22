{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @for ($i = 1; $i <= $sitemapCount; $i++)
    <sitemap>
        <loc>{{ url("/sitemap-$i.xml") }}</loc>
        <lastmod>{{ now()->toDateString() }}</lastmod>
    </sitemap>
    @endfor
</sitemapindex>
