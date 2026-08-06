@extends('layouts.app')

@section('content')


<div class="max-w-5xl mx-auto">


<div class="mb-6">

<h1 class="text-2xl font-bold text-slate-800">

Modifier la campagne

</h1>


<p class="text-sm text-gray-500">

Mise à jour des informations de la campagne.

</p>


</div>



<div class="bg-white rounded-xl shadow p-6">


<form method="POST"
      action="{{ route('campagnes.update',$campagne) }}">


@csrf

@method('PUT')


@include('campagnes._form')



</form>



</div>



</div>


@endsection