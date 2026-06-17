@extends('layouts.app')

@section('title', 'Registrar Pago')

@section('content')
<h2>Registrar Pago</h2>

@if(session('fail'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('fail') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form method="POST" action="{{ route('pagos.store') }}">
    @csrf
    @method('POST')
    <div class="mb-3">
        <label>Alumno</label>
        <select name="matricula" class="form-control" required>
            <option value="">Seleccione...</option>
            @foreach($alumnos as $alumno)
                <option value="{{ $alumno->matricula }}">{{ $alumno->matricula }} - {{ $alumno->nombre_completo }}</option>
            @endforeach
        </select>
        @error('matricula')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="mb-3">
        <label>Concepto</label>
        <input type="text" name="concepto" class="form-control" value="Colegiatura">
        @error('concepto')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="mb-3">
        <label>Monto</label>
        <input type="text" name="monto" class="form-control" required>
        @error('monto')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="mb-3">
        <label>Fecha de Pago</label>
        <input
            type="date"
            name="fecha_pago"
            class="form-control"
            value="{{ now()->format('Y-m-d') }}"
            min="{{ now()->subMonth()->toDateString() }}"
            max="{{ now()->toDateString() }}"
        >
        @error('fecha_pago')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="mb-3">
        <label>Método</label>
        <select name="metodo" class="form-control">
            <option value="efectivo">Efectivo</option>
            <option value="transferencia">Transferencia</option>
            <option value="tarjeta">Tarjeta</option>
        </select>
        @error('metodo')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="mb-3">
        <label>Sede ID</label>
        <input type="number" name="sede_id" class="form-control">
        @error('sede_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <button type="submit" class="btn btn-primary">Guardar Pago</button>
</form>
@endsection
