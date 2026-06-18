<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Kopi Subroto') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Bungee&family=Figtree:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-brand-cream font-sans text-brand-ink pt-16">
        <!-- Simplified Header -->
        <nav class="bg-brand-deep text-brand-parchment shadow-lg fixed top-0 w-full h-16 z-40">
            <div class="container mx-auto px-6 h-full flex justify-between items-center">
                <a href="/" class="brand-lockup cursor-pointer">
                    <span class="brand-emblem" aria-hidden="true">S</span>
                    <div class="flex flex-col justify-center min-w-0">
                        <h1 class="brand-wordmark text-[0.95rem] sm:text-lg text-brand-parchment">Kopi Subroto</h1>
                        <p class="hidden sm:block brand-subtitle text-[10px] text-brand-parchment/75 leading-tight">Kopi Premium & Snacks Enak</p>
                    </div>
                </a>
                <div>
                    <a href="/" class="text-sm font-semibold hover:text-brand-teal transition">Beranda</a>
                </div>
            </div>
        </nav>

        <div class="flex flex-col items-center justify-center py-12 px-4 min-h-[calc(100vh-4rem)]">
            <div class="w-full sm:max-w-md bg-white rounded-[2.5rem] shadow-xl border border-brand-line p-8 my-auto">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
