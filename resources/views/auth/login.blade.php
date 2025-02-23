@extends('layouts.app')

@section('content')
  <h2 class="text-uppercase text-center mb-5">Inicio de sesión</h2>

  <form action="{{ route('login.submit') }}" method="POST">
    @csrf 
    <x-form-input name="email" label="Correo"/> 
    
    <x-form-input name="password" type="password" label="Contraseña"/>
    
    <x-captcha/>
    
    <x-form-button :text="'Enviar'"/>

    <p class="text-center text-muted mt-5 mb-0">No tienes una cuenta? <a href="{{ route('register.form') }}"
        class="fw-bold text-body"><u>Registrate aquí</u></a></p> 
  </form>
  <script src="https://www.google.com/recaptcha/api.js"></script>
@endsection
