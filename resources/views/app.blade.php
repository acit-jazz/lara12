
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="apple-touch-icon" sizes="180x180" href="/images/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon/favicon-16x16.png">
        <meta property="article:modified_time" content="{{date('c')}}" />
        <title>{{ $meta['title'] ?? config('app.name',  env('social_id')) }}</title>
        <meta head-key="description" name="description" content="{{ $meta['title'] ?? '' }}"/>
        <meta head-key="og:type" name="og:type" content="article" />
        <meta head-key="og:title" property="og:title" content="{{ $meta['title'] }}"/>
        <meta head-key="og:description" property="og:description" content="{{ $meta['description'] ?? '' }}"/>
        <meta head-key="og:image" property="og:image" content="{{ $meta['image'] ?? '' }}"/>
        <meta head-key="og:image:secure" property="og:image:secure" content="{{ $meta['image'] ?? '' }}"/>
        <meta head-key="og:image:width" name="og:image:width" content="650" />
        <meta head-key="og:image:height" name="og:image:height" content="366" />

        <meta head-key="twitter:card" name="twitter:card" content="summary_large_image"/>
        <meta head-key="twitter:site" name="twitter:site" content="@{{ env('social_id') }}" />
        <meta head-key="twitter:site:id" name="twitter:site:id" content="@{{ env('social_id') }}" />
        <meta head-key="twitter:creator" name="twitter:creator" content="@{{ env('social_id') }}" />
        <meta head-key="twitter:description" name="twitter:description" content="{{ $meta['title'] ?? '' }}"/>
        <meta head-key="twitter:image" name="twitter:image" content="{{ $meta['image'] ?? '' }}"/>
        <link rel="canonical" href="{{request()->fullUrl() }}" />
        <link rel="alternate" href="{{request()->fullUrl() }}" hreflang="id" />
        @if (env('APP_ENV') != 'production')
        <meta name="robots" content="noindex">
        <meta name="googlebot" content="noindex">
        @else
        <meta name="robots" content="index, follow" />
        <meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
        <meta name="bingbot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
        @endif
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

        @inertiaHead
        @if (env('APP_ENV') == 'production')
        @else
        @endif

        @routes
        <script>
            Ziggy.url = '{{ env('APP_URL') }}'
        </script>
        @vite(['resources/js/app.js', "resources/js/frontend/{$page['component']}.vue"])
            @if (env('APP_ENV') == 'production')
             <!-- Google tag (gtag.js) Here-->
        @endif

    </head>
    <body class="font-primary antialiased">
        @if (env('APP_ENV') == 'production')
        <!-- Google Tag Manager (noscript) Here-->
        <!-- End Google Tag Manager (noscript) -->
        @endif
        @inertia

    </body>
</html>
