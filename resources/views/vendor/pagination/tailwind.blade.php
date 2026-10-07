@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex items-center justify-between">

        {{-- Version mobile --}}
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-md">
                    Précédent
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                    Précédent
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                    Suivant
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-md">
                    Suivant
                </span>
            @endif
        </div>

        {{-- Version ordinateur --}}
        <div class="hidden sm:flex sm:items-center sm:justify-between w-full">

            <div>
                {{-- <p class="text-sm text-gray-600">
                    Affichage de
                    <span class="font-semibold text-gray-900">
                        {{ $paginator->firstItem() }}
                    </span>
                    à
                    <span class="font-semibold text-gray-900">
                        {{ $paginator->lastItem() }}
                    </span>
                    sur
                    <span class="font-semibold text-gray-900">
                        {{ $paginator->total() }}
                    </span>
                    résultats
                </p> --}}
                <p class="text-sm text-gray-600">
                    <span class="font-semibold text-gray-900">
                        {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}
                    </span>
                    sur
                    <span class="font-semibold text-gray-900">
                        {{ $paginator->total() }}
                    </span>
                    résultats
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex rounded-md shadow-sm">

                    {{-- Précédent --}}
                    @if ($paginator->onFirstPage())
                        <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 cursor-default rounded-l-md">
                            ‹
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}"
                           class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50">
                            ‹
                        </a>
                    @endif

                    {{-- Pages --}}
                    @foreach ($elements as $element)

                        @if (is_string($element))
                            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300">
                                {{ $element }}
                            </span>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)

                                @if ($page == $paginator->currentPage())
                                    <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-[#006a4f] border border-[#006a4f]">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}"
                                       class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-[#e5f2ee]">
                                        {{ $page }}
                                    </a>
                                @endif

                            @endforeach
                        @endif

                    @endforeach

                    {{-- Suivant --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}"
                           class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50">
                            ›
                        </a>
                    @else
                        <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 cursor-default rounded-r-md">
                            ›
                        </span>
                    @endif

                </span>
            </div>

        </div>
    </nav>
@endif