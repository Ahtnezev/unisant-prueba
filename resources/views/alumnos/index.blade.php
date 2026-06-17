@extends('layouts.app')

@section('title', 'Alumnos')

@section('content')
<h2>Lista de Alumnos</h2>

@if(session('fail'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('fail') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

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
                <button type="button" class="btn btn-danger btn-sm"
                        onclick="confirmDelete({{ $alumno->id }}, '{{ $alumno->nombre_completo }}')">
                    <i class="fas fa-trash me-1"></i> Eliminar
                </button>
                <form id="delete-form-{{ $alumno->id }}"
                    method="POST"
                    action="{{ route('alumnos.destroy', $alumno->id) }}"
                    style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>



{{ $alumnos->links() }}

@endsection

@push('js')
    <script>
        function confirmDelete(id, nombre) {
            Swal.fire({
                title: '¿Eliminar alumno?',
                html: `¿Estás seguro de que deseas eliminar a <strong>${nombre}</strong>?<br><br>Esta acción no se puede deshacer.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return new Promise((resolve) => {
                        document.getElementById('delete-form-' + id).submit();
                        resolve();
                    });
                }
            });
        }
    </script>
@endpush
