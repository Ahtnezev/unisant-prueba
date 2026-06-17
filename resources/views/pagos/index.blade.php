@extends('layouts.app')

@section('title', 'Pagos')

@section('content')
<h2>Historial de Pagos</h2>

<table class="table">
    <thead>
        <tr>
            <th>Matrícula</th>
            <th>Concepto</th>
            <th>Monto</th>
            <th>Fecha</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pagos as $pago)
        <tr>
            <td>{{ $pago->matricula }}</td>
            <td>{{ $pago->concepto }}</td>
            <td>${{ $pago->monto }}</td>
            <td>{{ $pago->fecha_pago }}</td>
            <td>{{ $pago->estado }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
