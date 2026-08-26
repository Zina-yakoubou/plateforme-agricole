@extends('layouts.app')

@section('content')

<div class="mx-auto max-w-5xl space-y-6">

    {{-- EN-TÊTE --}}
    <div>

        <div class="flex items-center gap-3">

            <a
                href="{{ route('dpa.planification.index') }}"
                class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-500 hover:bg-gray-50"
            >
                ←
            </a>

            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Planifier une campagne
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Définissez les paramètres de planification de la campagne réceptionnée.
                </p>
            </div>

        </div>

    </div>


    {{-- FORMULAIRE --}}

    <form
        method="POST"
        action="{{ route('dpa.planification.store', $deploiement) }}"
        novalidate
    >

        @csrf

        @include('dpa.planification._form', [
            'deploiement' => $deploiement,
        ])

    </form>

</div>

@endsection