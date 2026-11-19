
@extends('layouts.workspace')

@section('title', 'Finanzas')
@section('eyebrow', 'Administración / Finanzas')
@section('page_title', 'Movimientos financieros')

@section('content')

    {{-- Resumen financiero --}}
    <div class="page-toolbar">
        <div>
            <h2>Resumen financiero</h2>

            <p>
                Consulta los ingresos, gastos
                y el balance de LÚMINA.
            </p>
        </div>
    </div>

    <div class="finance-summary">

        <div class="data-card" style="padding: 24px;">
            <p>Ingresos</p>

            <h2 style="color: #287a57;">
                ${{ number_format($ingresos, 2) }}
            </h2>
        </div>

        <div class="data-card" style="padding: 24px;">
            <p>Gastos</p>

            <h2 style="color: #b34b43;">
                ${{ number_format($gastos, 2) }}
            </h2>
        </div>

        <div class="data-card" style="padding: 24px;">
            <p>Balance</p>

            <h2>
                ${{ number_format($balance, 2) }}
            </h2>
        </div>

    </div>

    {{-- Registro de gastos manuales --}}
    <div class="editor-grid" style="margin-top: 28px;">

        <form
            class="editor-card"
            action="{{ route('finanzas.store') }}"
            method="POST"
        >
            @csrf

            <div class="editor-card__head">
                <span>
                    <x-icon name="wallet" />
                </span>

                <div>
                    <h2>Registrar gasto</h2>

                    <p>
                        Registra gastos operativos
                        adicionales de la joyería.
                    </p>
                </div>
            </div>

            <div class="form-grid">

                {{-- Concepto --}}
                <div class="field field--wide">
                    <label for="concepto">
                        Concepto
                    </label>

                    <input
                        class="input"
                        type="text"
                        name="concepto"
                        id="concepto"
                        value="{{ old('concepto') }}"
                        maxlength="255"
                        required
                    >

                    @error('concepto')
                        <p class="field__error">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Monto --}}
                <div class="field field--wide">
                    <label for="monto">
                        Monto ($)
                    </label>

                    <div class="input-prefix">
                        <span>$</span>

                        <input
                            class="input"
                            type="number"
                            name="monto"
                            id="monto"
                            min="0.01"
                            step="0.01"
                            value="{{ old('monto') }}"
                            required
                        >
                    </div>

                    @error('monto')
                        <p class="field__error">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>

            <div class="form-actions">
                <button
                    class="button button--primary"
                    type="submit"
                >
                    <x-icon name="check" size="18" />
                    Registrar gasto
                </button>
            </div>

        </form>

        {{-- Información adicional --}}
        <aside class="help-card">
            <span>
                <x-icon name="info" />
            </span>

            <h2>Control financiero</h2>

            <p>
                Las compras de inventario generan
                gastos automáticamente.
            </p>

            <p>
                Los ingresos por ventas se registran
                mediante la gestión de pedidos.
            </p>

            <p>
                Utiliza este formulario únicamente
                para gastos adicionales, como
                transporte, mantenimiento o servicios.
            </p>
        </aside>

    </div>

    {{-- Historial de movimientos --}}
    <div class="page-toolbar" style="margin-top: 36px;">
        <div>
            <h2>Historial de movimientos</h2>

            <p>
                Registro de ingresos y gastos
                realizados en LÚMINA.
            </p>
        </div>
    </div>

    <section class="data-card">

        <div class="table-wrap">

            <table class="data-table">

                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Concepto</th>
                        <th>Monto</th>
                        <th>Fecha</th>
                        <th>Pedido</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($movimientos as $movimiento)

                        <tr>

                            <td>
                                <span class="category-pill">
                                    {{ ucfirst($movimiento->tipo) }}
                                </span>
                            </td>

                            <td>
                                {{ $movimiento->concepto }}
                            </td>

                            <td>
                                <strong>
                                    ${{ number_format($movimiento->monto, 2) }}
                                </strong>
                            </td>

                            <td>
                                {{ $movimiento->fecha_movimiento }}
                            </td>

                            <td>
                                {{ $movimiento->id_pedido ?? '—' }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5">
                                No hay movimientos registrados.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </section>

<style>
    .finance-summary {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 20px;
    }

    .finance-summary .data-card {
        min-width: 0;
    }

    .finance-summary .data-card h2 {
        margin-top: 12px;
        overflow-wrap: anywhere;
    }

    @media (max-width: 900px) {
        .finance-summary {
            grid-template-columns: 1fr;
        }
    }
</style>

@endsection