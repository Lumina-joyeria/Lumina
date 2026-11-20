@extends('layouts.app')
@section('title', 'Pedido registrado | LÚMINA')

@section('content')
    <section class="store-section">
        <div class="container">
            <div class="empty-state">
                <span class="empty-state__icon"><x-icon name="check" size="28" /></span>
                <h1>Pedido #{{ $pedidoId }} registrado</h1>
                <p>Tu pedido está pendiente de confirmación.</p>
                <a class="button button--primary" href="{{ url('/') }}">Volver a la tienda</a>
            </div>
        </div>
    </section>
@endsection
