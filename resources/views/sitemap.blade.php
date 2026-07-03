{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Static Pages -->
    <url>
        <loc>{{ url('/') }}</loc>
        <priority>1.0</priority>
        <changefreq>daily</changefreq>
    </url>
    <url>
        <loc>{{ route('services.index') }}</loc>
        <priority>0.8</priority>
        <changefreq>weekly</changefreq>
    </url>
    <url>
        <loc>{{ route('projects.index') }}</loc>
        <priority>0.8</priority>
        <changefreq>weekly</changefreq>
    </url>
    <url>
        <loc>{{ route('team.index') }}</loc>
        <priority>0.7</priority>
        <changefreq>monthly</changefreq>
    </url>
    <url>
        <loc>{{ route('blog.index') }}</loc>
        <priority>0.9</priority>
        <changefreq>daily</changefreq>
    </url>

    <!-- Services Dynamic pages -->
    @foreach($services as $service)
        <url>
            <loc>{{ route('services.show', $service->slug) }}</loc>
            <lastmod>{{ $service->updated_at->toAtomString() }}</lastmod>
            <priority>0.7</priority>
            <changefreq>monthly</changefreq>
        </url>
    @endforeach

    <!-- Projects Dynamic pages -->
    @foreach($projects as $project)
        <url>
            <loc>{{ route('projects.show', $project->slug) }}</loc>
            <lastmod>{{ $project->updated_at->toAtomString() }}</lastmod>
            <priority>0.7</priority>
            <changefreq>monthly</changefreq>
        </url>
    @endforeach

    <!-- Team Members Dynamic pages -->
    @foreach($teamMembers as $member)
        <url>
            <loc>{{ route('team.show', $member->slug) }}</loc>
            <lastmod>{{ $member->updated_at->toAtomString() }}</lastmod>
            <priority>0.6</priority>
            <changefreq>monthly</changefreq>
        </url>
    @endforeach

    <!-- Blog Posts Dynamic pages -->
    @foreach($posts as $post)
        <url>
            <loc>{{ route('blog.show', $post->slug) }}</loc>
            <lastmod>{{ $post->updated_at->toAtomString() }}</lastmod>
            <priority>0.8</priority>
            <changefreq>weekly</changefreq>
        </url>
    @endforeach

    <!-- Careers Pages -->
    <url>
        <loc>{{ route('careers.index') }}</loc>
        <priority>0.8</priority>
        <changefreq>weekly</changefreq>
    </url>
    <url>
        <loc>{{ route('careers.jobs') }}</loc>
        <priority>0.8</priority>
        <changefreq>weekly</changefreq>
    </url>
    @foreach($jobs as $job)
        <url>
            <loc>{{ route('careers.show', $job->slug) }}</loc>
            <lastmod>{{ $job->updated_at->toAtomString() }}</lastmod>
            <priority>0.7</priority>
            <changefreq>monthly</changefreq>
        </url>
    @endforeach
</urlset>
