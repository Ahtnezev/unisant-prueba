@extends('layouts.app')

@section('title', 'Nuevo Alumno')

@section('content')
<h2>Registrar Alumno</h2>

<form method="POST" action="/alumnos">
    @csrf
    <div class="mb-3">
        <label>Matrícula</label>
        <input type="text" name="matricula" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Nombre Completo</label>
        <input type="text" name="nombre_completo" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Apellido Paterno</label>
        <input type="text" name="apat" class="form-control">
    </div>
    <div class="mb-3">
        <label>Apellido Materno</label>
        <input type="text" name="amat" class="form-control">
    </div>
    <div class="mb-3">
        <label>CURP</label>
        <input type="text" name="curp" class="form-control">
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control">
    </div>
    <div class="mb-3">
        <label>Teléfono</label>
        <input type="text" name="telefono" class="form-control">
    </div>
    <div class="mb-3">
        <label>Sede</label>
        <select name="sede_id" class="form-control" required>
            <option value="">Seleccione...</option>
            @foreach($sedes as $sede)
                <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Programa</label>
        <input type="number" name="programa_id" class="form-control" placeholder="ID del programa">
    </div>
    <div class="mb-3">
        <label>Monto Inicial</label>
        <input type="text" name="monto_inicial" class="form-control" placeholder="Ej: 1500.00">
    </div>
    <div class="mb-3">
        <label>Fecha de Nacimiento</label>
        <input type="date" name="fecha_nacimiento" class="form-control">
    </div>
    <!-- Nota: old() no es parte del flujo de este sistema -->
    <button type="submit" class="btn btn-primary">Guardar</button>
</form>
@endsection
