@extends('layouts.app')

@section('content')


<div class="max-w-5xl mx-auto">





{{-- En-tête --}}

<div class="flex items-center justify-between mb-6">


<div>


<h1 class="text-2xl font-bold text-slate-800">

Détail de la préfecture

</h1>


<p class="text-sm text-slate-500 mt-1">

Informations administratives de la préfecture.

</p>


</div>





<div class="flex gap-3">



<a href="{{ route('prefectures.edit',$prefecture) }}"
   class="px-5 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">


Modifier


</a>




<a href="{{ route('prefectures.index') }}"
   class="px-5 py-2 rounded-lg border border-gray-300">


Retour


</a>



</div>



</div>








{{-- Informations préfecture --}}

<div class="bg-white rounded-xl shadow p-6">



<div class="grid grid-cols-1 md:grid-cols-2 gap-6">



<div>

<p class="text-sm text-gray-500">

Nom de la préfecture

</p>


<p class="font-semibold text-slate-800 text-lg">

{{ $prefecture->nom }}

</p>


</div>





<div>

<p class="text-sm text-gray-500">

Code

</p>


<p class="font-semibold text-slate-800 text-lg">

{{ $prefecture->code }}

</p>


</div>





<div>

<p class="text-sm text-gray-500">

Région

</p>


<p class="font-semibold text-green-600">

{{ $prefecture->region->nom }}

</p>


</div>





<div>

<p class="text-sm text-gray-500">

Créée le

</p>


<p class="font-semibold">

{{ $prefecture->created_at?->format('d/m/Y') }}

</p>


</div>




</div>


</div>










{{-- Communes --}}

<div class="bg-white rounded-xl shadow mt-6 p-6">



<div class="flex justify-between items-center mb-4">


<h2 class="text-lg font-bold text-slate-800">

Communes rattachées

</h2>


{{-- futur bouton --}}
{{-- Ajouter une commune --}}
<a href="{{ route('communes.create', ['prefecture' => $prefecture->idPrefecture]) }}"
   class="px-5 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">

    + Ajouter une commune

</a>

</div>







@if($prefecture->communes->count())



<table class="w-full text-sm">



<thead class="bg-slate-100">


<tr>

<th class="px-5 py-3 text-left">
Nom
</th>


<th class="px-5 py-3 text-left">
Code
</th>


</tr>


</thead>





<tbody class="divide-y">



@foreach($prefecture->communes as $commune)



<tr>


<td class="px-5 py-3">

{{ $commune->nom }}

</td>



<td class="px-5 py-3">

{{ $commune->code }}

</td>


</tr>



@endforeach



</tbody>



</table>




@else


<p class="text-gray-500">

Aucune commune associée à cette préfecture.

</p>



@endif




</div>






</div>


@endsection