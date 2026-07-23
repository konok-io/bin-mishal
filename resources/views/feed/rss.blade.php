<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:dc="http://purl.org/dc/elements/1.1/">
    <channel>
        <title>{{ $siteName }}</title>
        <link>{{ $siteUrl }}</link>
        <description>{{ $siteDescription }}</description>
        <language>en</language>
        <copyright>Copyright {{ date('Y') }} {{ $siteName }}</copyright>
        <lastBuildDate>{{ now()->toRfc2822String() }}</lastBuildDate>
        <atom:link href="{{ url('/feed/rss') }}" rel="self" type="application/rss+xml" />
        
        @foreach($posts as $post)
        <item>
            <title><![CDATA[{{ $post->title }}]]></title>
            <link>{{ url('/blog/' . $post->slug) }}</link>
            <guid isPermaLink="true">{{ url('/blog/' . $post->slug) }}</guid>
            <description><![CDATA[{{ $post->excerpt ?? Str::limit(strip_tags($post->content), 200) }}]]></description>
            <content:encoded><![CDATA[{{ $post->content ?? $post->excerpt }}]]></content:encoded>
            @if($post->author)
            <dc:creator><![CDATA[{{ $post->author->name }}]]></dc:creator>
            @endif
            @if($post->category)
            <category><![CDATA[{{ $post->category->name }}]]></category>
            @endif
            <pubDate>{{ $post->published_at?->toRfc2822String() ?? now()->toRfc2822String() }}</pubDate>
            @if($post->featured_image)
            <enclosure url="{{ asset($post->featured_image) }}" type="image/jpeg" />
            @endif
        </item>
        @endforeach
    </channel>
</rss>
