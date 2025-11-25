<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom Simple Button Style -->
    <style>
        .btn-simple {
            background: #4da3ff;
            color: #fff;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            transition: 0.2s;
            font-weight: 600;
        }

        .btn-simple:hover {
            background: #1e7fe6;
            color: #fff;
        }

        .btn-simple-secondary {
            background: #e6e6e6;
            color: #333;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            transition: 0.2s;
            font-weight: 600;
        }

        .btn-simple-secondary:hover {
            background: #cfcfcf;
        }
    </style>
</head>

<body class="font-sans antialiased bg-white text-black">
    <div class="min-h-screen">

        {{-- Navigation --}}
        @include('layouts.navigation')

        {{-- Header Jetstream (dimatikan) --}}
        @isset($header)
            <header style="display:none">
                {{ $header }}
            </header>
        @endisset

        {{-- Page Content --}}
        <main class="py-4">
            <div class="container-lg px-4">
                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
