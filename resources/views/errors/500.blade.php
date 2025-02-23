@extends('layouts.app')

@section('content')
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        h1 {
            color:#7F1E57;
            font-size: 3rem;
            margin-top: 3rem;
        }
        .img1 {
            margin-top: 1rem;
            width: 50%;
        }
        p {
            color: black;
            font-size: 1.2rem;
        }
    </style>
     <h1>¡Oops! ocurrió un error.</h1>
     <h4>Vuelve más tarde.</h4>   
    <img alt="error" class="img1" src="{{URL::asset('img/cross.png')}}"/>

@endsection