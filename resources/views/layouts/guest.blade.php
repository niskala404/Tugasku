<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Small inline styles for animated gradient + card effects -->
        <style>
            /* animated background gradient */
            .animated-bg {
                position: absolute;
                inset: 0;
                z-index: -10;
                background: linear-gradient(270deg, #e6f6ff 0%, #dff3ff 25%, #ffffff 50%, #d9eefc 75%, #e6f6ff 100%);
                background-size: 600% 600%;
                animation: gradientShift 12s ease infinite;
            }

            @keyframes gradientShift {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }

            /* subtle radial highlight to add depth */
            .animated-bg::after {
                content: "";
                position: absolute;
                left: -10%;
                top: -20%;
                width: 140%;
                height: 140%;
                background: radial-gradient(ellipse at 10% 10%, rgba(255,255,255,0.65), rgba(255,255,255,0));
                pointer-events: none;
            }

            /* glass card look + float */
            .auth-card {
                background: linear-gradient(180deg, rgba(255,255,255,0.85), rgba(255,255,255,0.75));
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
                border: 1px solid rgba(191,219,254,0.45); /* soft blue border */
                box-shadow: 0 8px 30px rgba(2,6,23,0.08);
                border-radius: 1rem;
                animation: floatCard 6s ease-in-out infinite;
            }

            @keyframes floatCard {
                0% { transform: translateY(0px); }
                50% { transform: translateY(-6px); }
                100% { transform: translateY(0px); }
            }

            /* entrance for nicer loading */
            .enter {
                animation: enterUp 560ms cubic-bezier(.2,.9,.3,1) both;
            }
            @keyframes enterUp {
                from { opacity: 0; transform: translateY(10px) scale(.997); }
                to   { opacity: 1; transform: translateY(0) scale(1); }
            }

            /* logo bounce */
            .logo-bounce {
                display: inline-block;
                animation: logoFloat 3.6s ease-in-out infinite;
            }
            @keyframes logoFloat {
                0% { transform: translateY(0); }
                50% { transform: translateY(-6px) rotate(-1deg); }
                100% { transform: translateY(0); }
            }

            /* make sure motion respects user preference */
            @media (prefers-reduced-motion: reduce) {
                .animated-bg { animation: none; background-size: cover; }
                .auth-card { animation: none; }
                .logo-bounce { animation: none; }
                .enter { animation: none; }
            }

            /* small helpers for slot children (improve default buttons/links if they exist) */
            .auth-card :is(button, input[type="submit"]) {
                transition: transform .15s ease, box-shadow .15s ease;
            }
            .auth-card :is(button, input[type="submit"]):hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 22px rgba(2,6,23,0.09);
            }
        </style>
    </head>

    <body class="font-sans text-slate-800 antialiased">
        <div class="relative min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <!-- animated gradient background (behind everything) -->
            <div class="animated-bg" aria-hidden="true"></div>

            <!-- optional subtle pattern/overlay for contrast -->
            <div class="absolute inset-0 pointer-events-none -z-5"></div>

            <!-- Logo -->
            <div class="z-10">
                <a href="/" aria-label="{{ config('app.name', 'Laravel') }}">
                    <!-- logo-bounce adds a soft float to the logo -->
                    <x-application-logo class="logo-bounce w-20 h-20 text-blue-600" />
                </a>
            </div>

            <!-- Card (auth form will be injected into $slot) -->
            <div class="w-full sm:max-w-md mt-6 px-6 py-6 auth-card enter z-20">
                {{ $slot }}
            </div>

            <!-- small footer hint (optional) -->
            <div class="mt-4 text-sm text-slate-600 z-20 select-none">
                <!-- you can remove this or replace with your own small text -->
                <span>Keamanan & Privasi • Aplikasi {{ config('app.name', 'Laravel') }}</span>
            </div>
        </div>
    </body>
</html>
