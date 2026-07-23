@props([
    "title" => config("app.name"),
    "description" => __("predict::seo.home.description.text", fallback: "Prevedi il Futuro, Guadagna Crediti - La più grande piattaforma di prediction market"),
    "image" => asset("img/og-image.jpg"),
    "url" => url()->current(),
    "type" => "website",
    "siteName" => config("app.name"),
])

@php
    $language = str_replace("_", "-", app()->getLocale());
    $homeUrl = url("/");
    $host = request()->getHost();
    $websiteSchema = [
        "@context" => "https://schema.org",
        "@type" => "WebSite",
        "name" => $siteName,
        "url" => $homeUrl,
        "description" => $description,
        "inLanguage" => $language,
        "potentialAction" => [
            "@type" => "SearchAction",
            "target" => $homeUrl."/{search_term_string}",
            "query-input" => "required name=search_term_string",
        ],
    ];
    $organizationSchema = [
        "@context" => "https://schema.org",
        "@type" => "Organization",
        "name" => $siteName,
        "url" => $homeUrl,
        "logo" => asset("img/logo.png"),
        "description" => $description,
        "foundingDate" => "2024",
        "founders" => [
            [
                "@type" => "Person",
                "name" => "Base Predict Team",
            ],
        ],
        "contactPoint" => [
            "@type" => "ContactPoint",
            "contactType" => "customer support",
            "email" => "support@".$host,
        ],
        "sameAs" => [
            "https://twitter.com/".config("app.name"),
            "https://facebook.com/".config("app.name"),
            "https://linkedin.com/company/".config("app.name"),
        ],
    ];
    $webPageSchema = [
        "@context" => "https://schema.org",
        "@type" => "WebPage",
        "name" => $title,
        "description" => $description,
        "url" => $url,
        "inLanguage" => $language,
        "isPartOf" => [
            "@type" => "WebSite",
            "name" => $siteName,
            "url" => $homeUrl,
        ],
        "primaryImageOfPage" => [
            "@type" => "ImageObject",
            "url" => $image,
        ],
        "datePublished" => now()->toIso8601String(),
        "dateModified" => now()->toIso8601String(),
    ];
@endphp

<!-- SEO Meta Tags -->
<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<meta name="keywords" content="prediction market, betting, forecast, trading, credits, virtual currency, sports, politics, crypto">
<meta name="author" content="{{ config("app.name") }}">
<link rel="canonical" href="{{ $url }}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ $url }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $image }}">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:locale" content="{{ $language }}">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ $url }}">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $image }}">

<!-- JSON-LD Structured Data -->
<script type="application/ld+json">{!! json_encode($websiteSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
<script type="application/ld+json">{!! json_encode($organizationSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
<script type="application/ld+json">{!! json_encode($webPageSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
