@extends('layouts.app')

@section('title')
    Inicio
@endsection

@section('content')
    <div class="container py-5">
        <!-- Botones de acción -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-plus-circle me-2"></i>Crear encuesta
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('form-crear-encuesta', ['grupo' => 1]) }}">Una encuesta</a></li>
                        <li><a class="dropdown-item" href="#">Una plantilla para varias encuestas</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6 text-md-end mt-2 mt-md-0">
                <a href="#" class="btn btn-secondary">
                    <i class="bi bi-key me-2"></i>Tokens de Participantes
                </a>
            </div>
        </div>

        <!-- Filtros -->
        <form method="GET" action="{{ route('home') }}" class="card mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-center">
                    <div class="col-md-8">
                        <input type="text" name="buscar" class="form-control" placeholder="Buscar encuestas..." value="{{ request('buscar') }}">
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="completadas" id="encuestasCompletadas" value="1" {{ request('completadas') ? 'checked' : '' }}>
                            <label class="form-check-label" for="encuestasCompletadas">
                                Solo encuestas completadas
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Encuestas -->
        <h5 class="mb-3">Encuestas</h5>
        <div class="row g-3 mb-4">
            @forelse($encuestas as $encuesta)
                <x-encuesta.card
                    titulo="{{ $encuesta->titulo }}"
                    descripcion="{{ $encuesta->descripcion }}"
                    completada="{{ $encuesta->completada }}"
                    fechaTermino="{{ $encuesta->fechaTermino }}"
                    id="{{ $encuesta->id }}"
                    esEncuesta=true
                />
            @empty
                <div class="col-12">
                    <p class="text-muted">No hay encuestas disponibles.</p>
                </div>
            @endforelse
        </div>

        <!-- Plantillas -->
        <h5 class="mb-3">Plantillas</h5>
        <div class="row g-3">
            @forelse($plantillas as $plantilla)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-warning">
                        <div class="card-body">
                            <h5 class="card-title">{{ $plantilla->titulo }}</h5>
                            <p class="card-text text-muted small">{{ $plantilla->descripcion }}</p>
                            <p class="mb-0"><small><strong>Tipo:</strong> {{ $plantilla->tipoEncuesta->nombre ?? 'N/A' }}</small></p>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="d-flex gap-2">
                                <a href="#" class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <form action="#" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar esta plantilla?')">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-muted">No hay plantillas disponibles.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection