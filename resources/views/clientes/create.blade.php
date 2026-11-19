@extends('layouts.workspace')

@section('title', 'Nuevo cliente')
@section('eyebrow', 'Relaciones / Clientes')
@section('page_title', 'Nuevo cliente')

@section('content')
    @include('clientes._form', ['cliente' => null])
@endsection
