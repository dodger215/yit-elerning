<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="icon" href="/favicon.ico" type="image/x-icon">
        <link rel="icon" href="/images/logo.png" type="image/png" sizes="256x256">
        <link rel="apple-touch-icon" href="/images/logo.png">

        <!-- SEO Meta Tags -->
        <meta name="description" content="YouthInTech - Empowering the next generation of tech leaders through interactive learning, courses, and mentorship.">
        <meta name="keywords" content="Youth, Tech, E-learning, Mentorship, Courses, Education, Technology, Software Development">
        <meta name="author" content="YouthInTech">
        
        <!-- Open Graph / Social Media -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="{{ config('app.name', 'YouthInTech') }}">
        <meta property="og:description" content="Empowering the next generation of tech leaders through interactive learning and mentorship.">
        <meta property="og:image" content="{{ asset('images/logo.png') }}">
        
        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:url" content="{{ url()->current() }}">
        <meta name="twitter:title" content="{{ config('app.name', 'YouthInTech') }}">
        <meta name="twitter:description" content="Empowering the next generation of tech leaders through interactive learning and mentorship.">
        <meta name="twitter:image" content="{{ asset('images/logo.png') }}">

        @fonts
        @routes
        @vite(['resources/css/app.css', 'resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        <x-inertia::head>
            <title>{{ config('app.name', 'Laravel') }}</title>
        </x-inertia::head>
    </head>
    <body class="font-sans antialiased">
        <x-inertia::app />
    </body>
</html>
