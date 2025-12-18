@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center h-screen">
    <div class="w-full max-w-md bg-white p-8 rounded shadow">
        <h2 class="text-2xl font-bold mb-6 text-center">Connexion</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" required class="w-full p-2 border rounded mt-1">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Mot de passe</label>
                <input type="password" name="password" required class="w-full p-2 border rounded mt-1">
            </div>
            <div class="mb-4 text-right">
                <a href="{{ route('password.request') }}" class="text-blue-600 hover:underline text-sm">Mot de passe oublié ?</a>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700">Se connecter</button>
        </form>
    </div>
</div>
@endsection
