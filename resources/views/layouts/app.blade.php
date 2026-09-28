<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SIRA-Mô') }}</title>

    {{-- Police --}}
    <link rel="preconnect" href="https://fonts.bunny.net">

    <link
        href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap"
        rel="stylesheet"
    >

    {{-- Vite --}}
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    {{-- Alpine JS --}}
    <script
        defer
        src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js">
    </script>

    {{-- Styles globaux --}}
    <style>
        [x-cloak]{
            display:none !important;
        }

        html{
            scroll-behavior:smooth;
        }

        body{
            font-family:'Poppins',sans-serif;
            background:#f8faf9;
            overflow-x:hidden;
        }

        /* Scrollbar discrète */
        ::-webkit-scrollbar{
            width:6px;
            height:6px;
        }

        ::-webkit-scrollbar-thumb{
            background:#cbd5e1;
            border-radius:999px;
        }

        ::-webkit-scrollbar-track{
            background:transparent;
        }
    </style>

</head>

<body class="bg-[#f8faf9] text-slate-800 antialiased">

<div
    x-data="{
        sidebarOpen:false,
        sidebarCollapsed:false,

        toggleSidebar(){
            this.sidebarOpen=!this.sidebarOpen;
        },

        closeSidebar(){
            this.sidebarOpen=false;
        }
    }"
    class="relative flex min-h-screen"
>

    {{-- ================= OVERLAY MOBILE ================= --}}
    <div
        x-cloak
        x-show="sidebarOpen"
        x-transition.opacity
        @click="closeSidebar()"
        class="fixed inset-0 z-40 bg-black/40 lg:hidden">
    </div>

    {{-- ================= SIDEBAR ================= --}}
    @include('layouts.sidebar')

    {{-- ================= CONTENU ================= --}}
    <div class="flex min-w-0 flex-1 flex-col">

        {{-- Navbar --}}
        @include('layouts.navbar')

        {{-- Contenu principal --}}
        <main class="flex-1">

            <div class="w-full px-3 py-4 sm:px-5 lg:px-8 lg:py-6">

                @yield('content')

            </div>

        </main>

        {{-- Footer --}}
        @include('layouts.footer')

    </div>

</div>

{{-- Scripts spécifiques aux vues --}}
@stack('scripts')

</body>

</html>