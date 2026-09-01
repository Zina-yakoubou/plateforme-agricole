@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <div>

        <h1 class="text-2xl font-semibold text-[#212529]">
            Modifier l'affectation
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            {{ $affectation->reference }}
        </p>

    </div>


    @if(session('error'))

        <div class="rounded-lg border border-red-200
                    bg-red-50 px-4 py-3
                    text-sm text-red-700">

            {{ session('error') }}

        </div>

    @endif


    <div class="overflow-hidden rounded-lg
                border border-[#e5e7eb]
                bg-white
                shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

        <div class="border-b border-[#e5e7eb] px-6 py-5">

            <h2 class="font-semibold text-[#212529]">
                Informations de l'affectation
            </h2>

        </div>


        <form
            method="POST"
            action="{{ route(
                'affectations.update',
                $affectation
            ) }}"
        >

            @method('PUT')

            <div class="p-6">

                @include(
                    'dpa.affectations._form'
                )

            </div>


            <div class="flex items-center justify-end
                        gap-3 border-t border-[#e5e7eb]
                        bg-[#f8faf9] px-6 py-4">

                <a
                    href="{{ route(
                        'affectations.show',
                        $affectation
                    ) }}"
                    class="rounded-lg border border-[#e5e7eb]
                           bg-white px-4 py-2.5
                           text-sm font-semibold
                           text-gray-600
                           hover:bg-gray-50"
                >
                    Annuler
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-[#006a4f]
                           px-5 py-2.5
                           text-sm font-semibold
                           text-white
                           hover:bg-[#156c52]"
                >
                    Enregistrer les modifications
                </button>

            </div>

        </form>

    </div>

</div>

@endsection