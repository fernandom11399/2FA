@extends('layouts.app')

@section('content')
  <h2 class="text-uppercase text-center mb-5">Registro</h2>
  
  <form action="{{ route('register.submit') }}" method="POST">
    @csrf
  
    <x-form-input name="name" label="Nombre"/>
  
    <x-form-input name="email" type="email" label="Correo"/>
  
    <x-form-input name="password" type="password" label="Contraseña"/>
  
    <x-form-input name="password_confirmation" type="password" label="Repetir contraseña"/>
    
    <x-captcha/>
  
    <x-form-button :text="'Registrate'"/>
  
  
    <p class="text-center text-muted mt-5 mb-0">Ya tienes una cuenta? <a href="{{ route('login.form') }}"
        class="fw-bold text-body"><u>Inicia sesión aquí</u></a></p>
  </form> 
@endsection