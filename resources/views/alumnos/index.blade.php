@extends('layouts.app')

@section('title', 'Alumnos')

@section('content')
<h2>Lista de Alumnos</h2>

<form method="GET" action="/alumnos" class="mb-3">
    <div class="row">
        <div class="col-md-4">
            <input type="text" name="buscar" class="form-control" placeholder="Buscar..." value="{{ request('buscar') }}">
        </div>
        <div class="col-md-3">
            <select name="sede_id" class="form-control">
                <option value="">Todas las sedes</option>
                @foreach($sedes as $sede)
                    <option value="{{ $sede->id }}" {{ request('sede_id') == $sede->id ? 'selected' : '' }}>{{ $sede->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </div>
    </div>
</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Matrícula</th>
            <th>Nombre</th>
            <th>Sede</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($alumnos as $alumno)
        <tr>
            <td>{{ $alumno->matricula }}</td>
            <td>{{ $alumno->nombre_completo }}</td>
            <td>{{ $alumno->sede->nombre ?? 'N/A' }}</td>
            <td>{{ $alumno->estado }}</td>
            <td>
                <a href="/alumnos/eliminar/{{ $alumno->id }}" class="btn btn-danger btn-sm">Eliminar</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
