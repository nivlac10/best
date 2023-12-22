{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($files as $file)
    <url>
        <loc>{{ url('/article/' . $file->getFilename()) }}</loc>
        <lastmod>{{ $lastmod }}</lastmod>
    </url>
    @endforeach
</urlset>
