@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-3xl font-bold mb-6">Dashboard Admin</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-4 rounded shadow">Élèves : {{ $total_eleves }}</div>
        <div class="bg-white p-4 rounded shadow">Professeurs : {{ $total_profs }}</div>
        <div class="bg-white p-4 rounded shadow">Cours : {{ $total_cours }}</div>
    </div>
    <!-- Ajouter graphiques et tableaux avec Tailwind -->
</div>
@endsection
