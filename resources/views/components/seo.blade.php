@php
    $schema = $seo['schema'] ?? [];
    $contextKey = chr(64) . 'context';
    $graphKey = chr(64) . 'graph';
@endphp
<title>{{ $seo['title'] }}</title>
<meta name="description" content="{{ $seo['description'] }}">
<meta name="robots" content="{{ $seo['robots'] }}">
<link rel="canonical" href="{{ $seo['canonical'] }}">
<meta property="og:locale" content="{{ $seo['locale'] }}">
<meta property="og:type" content="{{ $seo['type'] }}">
<meta property="og:title" content="{{ $seo['title'] }}">
<meta property="og:description" content="{{ $seo['description'] }}">
<meta property="og:url" content="{{ $seo['canonical'] }}">
<meta property="og:site_name" content="{{ config('seo.site_name') }}">
@if(!empty($seo['publisher']))
<meta property="article:publisher" content="{{ $seo['publisher'] }}">
@endif
@if(!empty($seo['published_at']))
<meta property="article:published_time" content="{{ $seo['published_at'] }}">
@endif
@if(!empty($seo['modified_at']))
<meta property="article:modified_time" content="{{ $seo['modified_at'] }}">
@endif
@if(!empty($seo['image']))
<meta property="og:image" content="{{ $seo['image'] }}">
@endif
<meta name="twitter:card" content="{{ $seo['twitter_card'] }}">
<meta name="twitter:site" content="{{ $seo['twitter_site'] }}">
@if(!empty($seo['twitter_label1']) && !empty($seo['twitter_data1']))
<meta name="twitter:label1" content="{{ $seo['twitter_label1'] }}">
<meta name="twitter:data1" content="{{ $seo['twitter_data1'] }}">
@endif
@if(!empty($schema))
<script type="application/ld+json">
{!! json_encode([
    $contextKey => 'https://schema.org',
    $graphKey => $schema,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endif
