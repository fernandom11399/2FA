@extends('layouts.app')

@section('content')
  <h2 class="text-uppercase text-center mb-5">Verificación de Código</h2>
  <small class="text-lowercase">Te enviamos un código de verificación a tu correo. Ingrésalo aquí:</small>
  <form action="{{ route('verification.code') }}" method="POST">
      @csrf
      
      <x-form-input name="verification_code" label="Codigo"/>
      
      <input type="hidden" name="signed_url" value="{{ $signedUrl ?? old('signed_url') }}">
  
      <button type="submit" class="btn btn-primary">Verificar</button>
  </form>
@endsection