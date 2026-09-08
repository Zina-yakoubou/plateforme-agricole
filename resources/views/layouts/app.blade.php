<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>


<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<meta name="csrf-token" content="{{ csrf_token() }}">

<title>
    {{ config('app.name', 'Recensement Agricole') }}
</title>

{{-- ============================================================
    POLICE
============================================================ --}}
<link rel="preconnect" href="https://fonts.bunny.net">

<link
    href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap"
    rel="stylesheet"
/>


{{-- ============================================================
    ASSETS VITE
============================================================ --}}
@vite([
    'resources/css/app.css',
    'resources/js/app.js'
])


{{-- ============================================================
    ALPINE JS
============================================================ --}}
<script
    defer
    src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"
></script>


{{-- ============================================================
    STYLES
============================================================ --}}
<style>

    [x-cloak] {
        display: none !important;
    }

    .font-poppins,
    body {
        font-family:
            'Poppins',
            system-ui,
            -apple-system,
            "Segoe UI",
            Roboto,
            "Helvetica Neue",
            Arial,
            sans-serif;
    }

</style>


</head>

<body class="font-poppins antialiased bg-[#f8faf9]">

<div
    class="flex min-h-screen"
    x-data="{
        sidebarOpen: false,
        sidebarCollapsed: false
    }"
>


{{-- ============================================================
    SIDEBAR
============================================================ --}}
@include('layouts.sidebar')


<div class="flex-1 flex flex-col min-w-0">

    {{-- ========================================================
        NAVBAR
    ========================================================= --}}
    @include('layouts.navbar')


    {{-- ========================================================
        ZONE PRINCIPALE
    ========================================================= --}}
    <main class="flex-1 flex flex-col">

        {{-- ====================================================
            CONTENU DES PAGES
        ==================================================== --}}
        <div class="flex-1 p-6">

            @yield('content')

        </div>


        {{-- ====================================================
            FOOTER
        ==================================================== --}}
        @include('layouts.footer')

    </main>

</div>


</div>

{{-- ================================================================
SCRIPTS DES VUES

```
Permet aux vues comme maisons/form.blade.php d'utiliser :

    @push('scripts')
        <script>
            ...
        </script>
    @endpush

notamment pour la géolocalisation GPS.
```

================================================================ --}}
@stack('scripts')

</body>

</html>
