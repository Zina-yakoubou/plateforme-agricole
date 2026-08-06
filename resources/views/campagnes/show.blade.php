@extends('layouts.app')

@section('content')


<div class="max-w-5xl mx-auto">



<div class="flex justify-between mb-6">


<div>

<h1 class="text-2xl font-bold text-slate-800">

Détail de la campagne

</h1>


<p class="text-sm text-gray-500">

Informations générales.

</p>


</div>



<div class="flex gap-3">


<a href="{{ route('campagnes.edit',$campagne) }}"
class="px-5 py-2 bg-blue-600 text-white rounded-lg">

Modifier

</a>



<a href="{{ route('campagnes.index') }}"
class="px-5 py-2 border rounded-lg">

Retour

</a>


</div>


</div>





<div class="bg-white rounded-xl shadow p-6">


<div class="grid grid-cols-1 md:grid-cols-2 gap-6">



<div>

<p class="text-sm text-gray-500">

Code RNA

</p>

<p class="font-semibold">

{{ $campagne->codeRNA }}

</p>

</div>




<div>

<p class="text-sm text-gray-500">

Libellé

</p>

<p class="font-semibold">

{{ $campagne->libelle }}

</p>

</div>




<div>

<p class="text-sm text-gray-500">

Date début

</p>

<p class="font-semibold">

{{ $campagne->dateDebut?->format('d/m/Y') }}

</p>

</div>




<div>

<p class="text-sm text-gray-500">

Date fin

</p>

<p class="font-semibold">

{{ $campagne->dateFin?->format('d/m/Y') ?? '-' }}

</p>

</div>




<div>

<p class="text-sm text-gray-500">

Responsable

</p>

<p class="font-semibold">

{{ $campagne->responsable?->name ?? 'Non défini' }}

</p>

</div>




<div>

<p class="text-sm text-gray-500">

Statut

</p>

<p class="font-semibold text-green-600">

{{ $campagne->statut }}

</p>

</div>


</div>


</div>





<div class="bg-white rounded-xl shadow mt-6 p-6">


<h2 class="text-lg font-bold mb-4">

Affectations liées

</h2>


<p class="text-gray-500">

Nombre d'agents affectés :

<strong>

{{ $campagne->affectations->count() }}

</strong>


</p>


</div>




</div>


@endsection