@extends('layouts.workspace')

@section('title', 'Editar cliente')
@section('eyebrow', 'Relaciones / Clientes')
@section('page_title', 'Editar cliente')

@section('content')
    @include('clientes._form', ['cliente' => $cliente])
@endsection
