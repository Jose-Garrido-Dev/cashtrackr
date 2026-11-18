
    @extends('layouts.auth')

    @section('title')
        
    Administra tus presupuestos
    @endsection

    @section('auth-contents')
        <h1 class="mt-5 text-4xl">Administra tus presupuestos</h1>

        @if(session('success'))
            <p class="my-10 text-center border border-green-400 bg-green-100 py-3 text-sm text-green-700">{{ session('success') }}</p>
        @endif
    @endsection