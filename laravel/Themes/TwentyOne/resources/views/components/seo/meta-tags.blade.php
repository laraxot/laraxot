@props(['predict'])

{{-- Title Tag --}}
<title>{{ $predict->title ?? 'Predict' }} | Prediction Market - PredictMarket</title>

{{-- Meta Description --}}
@php
    $description = $predict->description ?? 'Scommetti su ' . ($predict->title ?? 'questo mercato') . '. Mercati di predizione con odds in tempo reale, order book e trading.';
    $description = Str::limit(strip_tags($description), 155);
@endphp
<meta name="description" content="{{ $description }}">

{{-- Canonical URL --}}
<link rel="canonical" href="{{ url()->current() }}">

{{-- Open Graph / Facebook --}}
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="{{ $predict->title ?? 'Prediction Market' }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $predict->image_url ?? asset('images/og-default.jpg') }}">
<meta property="og:site_name" content="PredictMarket">

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ url()->current() }}">
<meta name="twitter:title" content="{{ $predict->title ?? 'Prediction Market' }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $predict->image_url ?? asset('images/og-default.jpg') }}">

{{-- Additional SEO Meta Tags --}}
<meta name="robots" content="index, follow">
<meta name="googlebot" content="index, follow">
<meta name="theme-color" content="#0f172a">
