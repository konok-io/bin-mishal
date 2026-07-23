<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<feed xmlns="http://www.w3.org/2005/Atom" xml:lang="en">
    <title>{{ $siteName }}</title>
    <subtitle>{{ $siteDescription }}</subtitle>
    <id>{{ $siteUrl }}/</id>
    <updated>{{ $updatedAt->toIso8601String() }}</updated>
    <link href="{{ $siteUrl }}/feed/atom" rel="self" type="application/atom+xml" />
    <link href="{{ $siteUrl }}" rel="alternate" type="text/html" />
    <generator uri="https://laravel.com">Laravel</generator>
    
    @foreach($posts as $post)
    <entry>
        <title type="html"><![CDATA[{{ $post->title }}]]></title>
        <id>{{ $siteUrl }}/blog/{{ $post->slug }}</id>
        <updated>{{ ($post->updated_at ?? $post->published_at ?? now())->toIso8601String() }}</updated>
        <published>{{ ($post->published_at ?? now())->toIso8601String() }}</published>
        <link href="{{ $siteUrl }}/blog/{{ $post->slug }}" rel="alternate" type="text/html" />
        <summary type="html"><![CDATA[{{ $post->excerpt ?? Str::limit(strip_tags($post->content), 200) }}]]></summary>
        <content type="html"><![CDATA[{{ $post->content ?? $post->excerpt }}]]></content>
        @if($post->author)
        <author>
            <name>{{ $post->author->name }}</name>
        </author>
        @endif
        @if($post->category)
        <category term="{{ $post->category->name }}" />
        @endif
        @if($post->featured_image)
        <media:content xmlns:media="http://search.yahoo.com/mrss/" url="{{ asset($post->featured_image) }}" medium="image" />
        @endif
    </entry>
    @endforeach
</feed>
