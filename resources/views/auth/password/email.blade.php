@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center h-screen">
    <div class="w-full max-w-md bg-white p-8 rounded shadow">
        <h2 class="text-2xl font-bold mb-6 text-center">Réinitialiser le mot de passe</h2>
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" required class="w-full p-2 border rounded mt-1">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700">Envoyer le lien</button>
        </form>
    </div>
</div>
@endsection
