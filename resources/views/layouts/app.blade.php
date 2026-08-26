<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Recensement Agricole') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }

        .font-poppins,
        body {
            font-family: 'Poppins', system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
    </style>

</head>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<body class="font-poppins antialiased bg-[#f8faf9]">


<div class="flex min-h-screen" x-data="{ sidebarOpen: false, sidebarCollapsed: false }">


    {{-- Sidebar --}}
    @include('layouts.sidebar')



    <div class="flex-1 flex flex-col min-w-0">



        {{-- Navbar --}}
        @include('layouts.navbar')



        {{-- Contenu --}}
        <main class="flex-1 p-6">

            @yield('content')

        </main>



    </div>


</div>


</body>

</html>