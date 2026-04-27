@extends('layouts.app')

@section('title')
    Inicio
@endsection

@section('content')
<div class="container py-5" style="max-width: 1600px;">
    
    <div class="row mb-5 align-items-center">
        <div class="col-md-8">
            <h1 class="display-5 fw-bold text-dark mb-1">Panel de Encuestas</h1>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <div class="d-flex gap-2 justify-content-md-end">
                <div class="dropdown">
                    <button class="btn btn-guinda btn-lg dropdown-toggle rounded-pill shadow-sm" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-plus-lg me-2"></i>Crear Nuevo
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('form-crear-encuesta', ['grupo' => 1]) }}">
                                <i class="bi bi-journal-plus me-2 text-guinda"></i>Nueva Encuesta
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="#">
                                <i class="bi bi-layers me-2 text-guinda"></i>Nueva Plantilla
                            </a>
                        </li>
                    </ul>
                </div>
                <a href="#" class="btn btn-outline-secondary btn-lg rounded-pill shadow-sm" title="Gestionar Tokens">
                    <i class="bi bi-key"></i>
                    Gestionar Tokens
                </a>
            </div>
        </div>
    </div>

    {{-- Barra de Búsqueda y Filtros --}}
    <div class="card border-0 shadow-sm rounded-4 mb-5 overflow-hidden">
        <div class="card-body p-1 bg-white">
            <form id="form-busqueda" action="{{ route('buscar-encuestas') }}" class="row g-0">
                <div class="col-md-7">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-0 ps-4">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" id="input-buscar" name="buscar" 
                               class="form-control border-0 py-4 shadow-none" 
                               placeholder="Buscar por título...">
                    </div>
                </div>
                <div class="col-md-3 d-flex align-items-center justify-content-center border-start border-end my-2">
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" name="completadas" id="encuestasCompletadas" value="1">
                        <label class="form-check-label fw-medium text-secondary" for="encuestasCompletadas">Solo concluidas</label>
                    </div>
                </div>
                <div class="col-md-2 p-2">
                    <button type="submit" class="btn btn-guinda w-100 h-100 rounded-3 fw-bold">
                        Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-3 d-flex justify-content-between align-items-center">
            <h4 class="fw-bold text-dark-emphasis mb-0">Encuestas</h4>
        </div>
    </div>

    <div class="row g-4" id="encuestas-plantillas">
        @forelse($encuestas as $encuesta)
            <x-encuesta.card
                :titulo="$encuesta->titulo"
                :descripcion="$encuesta->descripcion"
                :completada="$encuesta->completada"
                :fechaInicio="$encuesta->fechaInicio"
                :fechaTermino="$encuesta->fechaTermino"
                :id="$encuesta->id"
                :esEncuesta="true"
                :grupo="$encuesta->nombreGrupo"
            />
        @empty
            <div class="col-12 text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-search text-muted display-1 opacity-25"></i>
                </div>
                <h4 class="text-muted">No se encontró ninguna encuesta</h4>
            </div>
        @endforelse
    </div>
</div>

<style>
    
</style>
@endsection