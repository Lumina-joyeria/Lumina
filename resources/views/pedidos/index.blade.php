@extends('layouts.workspace')

@section('title', 'Pedidos')
@section('eyebrow', 'Operaciones')
@section('page_title', 'Pedidos')

@section('content')
    <div class="page-toolbar">
        <div>
            <h2>Pedidos registrados</h2>
            <p>{{ $pedidos->count() }} {{ $pedidos->count() === 1 ? 'pedido' : 'pedidos' }}</p>
        </div>
        <a class="button button--primary" href="{{ route('pedidos.create') }}">
            <x-icon name="plus" size="18" /> Nuevo pedido
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert--error" role="alert">
            <span class="alert__icon"><x-icon name="alert" /></span>
            <div>
                <strong>No se pudo completar la acción</strong>
                @foreach ($errors->all() as $mensaje)
                    <p>{{ $mensaje }}</p>
                @endforeach
            </div>
        </div>
    @endif

    @php
        $estilos = [
            'Pendiente' => 'bg-amber-50 text-amber-900',
            'Confirmado' => 'bg-emerald-50 text-emerald-900',
            'Entregado' => 'bg-slate-100 text-slate-800',
            'Cancelado' => 'bg-rose-50 text-rose-900',
        ];
    @endphp

    <div class="grid gap-4">
        @forelse ($pedidos as $pedido)
            <article class="panel-card" aria-labelledby="pedido-{{ $pedido->id }}">
                <div class="panel-card__head flex-wrap gap-3">
                    <div>
                        <h2 id="pedido-{{ $pedido->id }}">Pedido #{{ $pedido->id }}</h2>
                        <p class="muted-text">
                            {{ $pedido->cliente?->nombre ?? 'Cliente no disponible' }} ·
                            {{ \Illuminate\Support\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    <span class="rounded-full px-3 py-1 text-sm font-semibold {{ $estilos[$pedido->estado] ?? 'bg-slate-100 text-slate-800' }}">
                        {{ $pedido->estado }}
                    </span>
                </div>

                @if ($pedido->detalles->isEmpty())
                    <p class="muted-text">Este pedido no tiene productos registrados.</p>
                @else
                    <ul class="m-0 grid list-none gap-3 p-0">
                        @foreach ($pedido->detalles as $detalle)
                            <li class="flex flex-wrap justify-between gap-2 border-b border-[var(--line)] pb-2">
                                <span>
                                    <strong>{{ $detalle->producto?->nombre ?? 'Producto no disponible' }}</strong>
                                    <span class="muted-text">
                                        · {{ $detalle->cantidad }} × ${{ number_format($detalle->precio_unitario, 2) }}
                                    </span>
                                </span>
                                <strong>${{ number_format($detalle->cantidad * $detalle->precio_unitario, 2) }}</strong>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <p class="mt-4 font-semibold">
                    Total: ${{ number_format($pedido->detalles->sum(fn ($detalle) => $detalle->cantidad * $detalle->precio_unitario), 2) }}
                </p>

                @if ($pedido->estado === 'Pendiente' || $pedido->estado === 'Confirmado')
                    <div class="mt-4 flex flex-wrap gap-2">
                        @if ($pedido->estado === 'Pendiente')
                            <form action="{{ route('pedidos.confirmar', $pedido->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button class="button button--primary" type="submit">Confirmar pedido</button>
                            </form>
                            <form
                                action="{{ route('pedidos.cancelar', $pedido->id) }}"
                                method="POST"
                                data-order-cancel="¿Cancelar el pedido #{{ $pedido->id }}?"
                            >
                                @csrf
                                @method('PATCH')
                                <button class="button button--ghost" type="submit">Cancelar pedido</button>
                            </form>
                        @else
                            <form action="{{ route('pedidos.entregar', $pedido->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button class="button button--primary" type="submit">Marcar como entregado</button>
                            </form>
                        @endif
                    </div>
                @endif
            </article>
        @empty
            <x-empty-state
                icon="cart"
                title="No hay pedidos registrados"
                description="Los pedidos aparecen aquí con su estado y sus productos."
                action="Registrar pedido"
                :href="route('pedidos.create')"
            />
        @endforelse
    </div>
    <script src="{{ asset('js/pedidos.js') }}" defer></script>
@endsection
