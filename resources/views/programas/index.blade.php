@extends('layouts.app')

@section('title', 'Programas')

@section('content')
<h2>Programas Académicos</h2>

<table class="table">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Inscripción</th>
            <th>Materia</th>
            <th>Total Materias</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($programas as $programa)
        <tr>
            <td>{{ $programa->nombre }}</td>
            <td>${{ $programa->inscripcion }}</td>
            <td>${{ $programa->precio_materia }}</td>
            <td>{{ $programa->materias->count() }}</td>
            <td>
                <button class="btn btn-sm btn-info">Ver</button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
