@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Alumnos Activos</h5>
                <p class="card-text display-4">{{ $alumnosActivos }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-secondary">
            <div class="card-body">
                <h5 class="card-title">Total Alumnos</h5>
                <p class="card-text display-4">{{ $totalAlumnos }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Pagos del Mes</h5>
                <p class="card-text display-4">${{ $pagosMes }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title">Inscripciones</h5>
                <p class="card-text display-4">{{ $totalInscripciones }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Promedio de Pago</div>
            <div class="card-body">
                <h3>${{ number_format($promedioPago, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Top Programas</div>
            <div class="card-body">
                <ul>
                    @foreach($programasTop as $programa)
                        <li>{{ $programa->nombre }} ({{ $programa->inscripciones->count() }} inscritos)</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
