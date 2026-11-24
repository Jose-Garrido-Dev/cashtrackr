
    @extends('layouts.auth')

    @section('title')
        Confirma tu cuenta
    @endsection

    @section('auth-contents')
        <p class="mt-5 text-lg">Tu cuenta fue creada con éxito , Ahora solo debes confirmarla, Revisa tu Email</p>


        @if(session('success'))
            <x-alert :message="session('success')" />
        @endif



        <form action="{{route('verification.send')}}" method="POST">
            <input type="submit"
            class="bg-amber-500 w-full text-center mt-5 px-5 py-2 uppercase font-bold cursor-pointer"
            value="REENVIAR CORREO DE VERIFICACIÓN">
        </form>
    @endsection