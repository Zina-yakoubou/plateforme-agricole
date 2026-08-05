@extends('layouts.accueil')

@section('content')

    @include('components.hero')
    @include('components.objectives')
    @include('components.modules')

    @include('components.process')
    {{-- @include('components.advantages') --}}
    @include('components.contact')
    @include('components.footer')

@endsection