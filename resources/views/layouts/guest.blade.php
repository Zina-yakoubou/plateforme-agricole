<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SIRA-Mô — Système d'Information et de Recensement Agricole</title>

    <link
        rel="icon"
        href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='14' fill='%23006a4f'/%3E%3Cpath d='M32 49V18' stroke='white' stroke-width='4' stroke-linecap='round'/%3E%3Cpath d='M32 25C25 18 17 20 14 21C16 29 23 34 32 32' stroke='white' stroke-width='4' stroke-linecap='round' stroke-linejoin='round'/%3E%3Cpath d='M32 32C39 25 47 27 50 28C48 36 41 40 32 39' stroke='white' stroke-width='4' stroke-linecap='round' stroke-linejoin='round'/%3E%3Ccircle cx='32' cy='17' r='3.5' fill='%2343a842'/%3E%3C/svg%3E"
    >

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 font-['Plus_Jakarta_Sans'] text-slate-800 antialiased">

    <div class="flex min-h-screen flex-col">

        <header class="shrink-0 border-b border-slate-200 bg-white">

            <div class="border-b border-slate-100 bg-[#006a4f]">
                <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-1.5 text-[10px] text-white sm:px-6 lg:px-8">

                    <span>
                        Préfecture de Mô • République Togolaise
                    </span>

                    <span class="hidden sm:block">
                        Application Web de Recensement Agricole
                    </span>

                </div>
            </div>

            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#006a4f]">

                        <svg
                            viewBox="0 0 64 64"
                            class="h-7 w-7"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                            aria-hidden="true"
                        >
                            <path
                                d="M32 49V18"
                                stroke="white"
                                stroke-width="4"
                                stroke-linecap="round"
                            />

                            <path
                                d="M32 25C25 18 17 20 14 21C16 29 23 34 32 32"
                                stroke="white"
                                stroke-width="4"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />

                            <path
                                d="M32 32C39 25 47 27 50 28C48 36 41 40 32 39"
                                stroke="white"
                                stroke-width="4"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />

                            <circle
                                cx="32"
                                cy="17"
                                r="3.5"
                                fill="#43a842"
                            />
                        </svg>

                    </div>

                    <div>
                        <p class="text-[10px] font-medium uppercase tracking-wider text-slate-400">
                            Système agricole
                        </p>

                        <p class="text-base font-bold leading-tight text-[#006a4f]">
                            SIRA-Mô
                        </p>
                    </div>

                </div>

                <div>
                    @auth
                        <a
                            href="{{ url('/dashboard') }}"
                            class="rounded-md bg-[#006a4f] px-3 py-2 text-xs font-semibold text-white transition hover:bg-[#00523d]"
                        >
                            Tableau de bord
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="rounded-md bg-[#43a842] px-3 py-2 text-xs font-semibold text-white transition hover:bg-[#378736]"
                        >
                            Se connecter
                        </a>
                    @endauth
                </div>

            </div>

        </header>

        <main class="flex-1">
            {{ $slot }}
        </main>

        <footer class="shrink-0 border-t border-slate-200 bg-white">

            <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-2 px-4 py-3 text-center sm:flex-row sm:text-left">

                <p class="text-[10px] font-medium text-slate-500">
                    SIRA-Mô
                    <span class="mx-1 text-slate-300">•</span>
                    Système d'Information et de Recensement Agricole
                </p>

                <p class="text-[10px] text-slate-400">
                    Préfecture de Mô
                    <span class="mx-1 text-slate-300">•</span>
                    Togo
                    <span class="mx-1 text-slate-300">•</span>
                    © {{ date('Y') }}
                </p>

            </div>

        </footer>

    </div>

</body>

</html>