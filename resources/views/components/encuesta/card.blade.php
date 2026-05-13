@props([
    'titulo' => "",
    'descripcion' => "",
    'completada' => false,
    'fechaInicio' => "",
    'fechaTermino' => "",
    'id' => null,
    'esEncuesta' => true,
    'grupo' => '',
])

<div class="col-md-6 col-lg-4 mb-4">
    <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden card-hover">
        
        <div class="py-1 px-3 {{ $esEncuesta ? 'bg-guinda' : 'bg-secondary' }} text-white d-flex justify-content-between align-items-center">
            <small class="fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">
                {{ $esEncuesta ? 'Encuesta' : 'Plantilla' }}
            </small>
            <i class="bi {{ $esEncuesta ? 'bi-journal-check' : 'bi-layers' }}"></i>
        </div>

        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="card-title fw-bold text-dark mb-0 text-truncate" title="{{ $titulo }}">
                    {{ $titulo }}
                </h5>
            </div>
            
            <p class="card-text text-muted small mb-3 line-clamp-2" style="height: 2.5rem;">
                {{ $descripcion ?: 'Sin descripción disponible.' }}
            </p>

            <div class="d-flex flex-wrap gap-2 mb-3">
                
                @if($completada)
                    <span class="badge rounded-pill bg-success-subtle text-success border border-success">
                        <i class="bi bi-check-all me-1"></i> Concluida
                    </span>
                @elseif (\Carbon\Carbon::parse($fechaInicio)->isFuture())
                    <span class="badge rounded-pill bg-info-subtle text-info-emphasis border border-info">
                        <i class="bi bi-calendar-event me-1"></i> Programada
                    </span>
                @else
                    <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis border border-primary">
                        <i class="bi bi-play-circle me-1"></i> En curso
                    </span>
                @endif

                <span class="badge rounded-pill bg-light text-dark border">
                    <i class="bi bi-people me-1"></i> {{ $grupo }}
                </span>
            </div>

            <div class="text-muted" style="font-size: 0.85rem;">
                <div class="d-flex align-items-center mb-1">
                    <i class="bi bi-calendar-range me-2 text-guinda"></i>
                    <span>{{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/y') }} - {{ \Carbon\Carbon::parse($fechaTermino)->format('d/m/y') }}</span>
                </div>
            </div>
        </div>

        <!-- Botones -->
        <div class="card-footer bg-white border-top-0 p-4 pt-0">
            <div class="d-grid gap-2">
                
                <a href="{{ route($esEncuesta ? 'editar-encuesta' : 'editar-plantilla', ['id' => $id]) }}" 
                   class="btn btn-guinda rounded-pill fw-bold">
                    <i class="bi bi-gear-fill me-2"></i> Gestionar Encuesta
                </a>
                
                <div class="d-flex gap-2 mt-1">
                    <div class="dropdown flex-grow-1">
                        <button class="btn btn-outline-secondary w-100 rounded-pill dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots me-1"></i> Más
                        </button>
                        <ul class="dropdown-menu shadow border-0">
                            <li><a class="dropdown-item" href="{{ route('form-preguntas-encuesta', ['id' => $id]) }}"><i class="bi bi-question-circle me-2"></i> Editar Preguntas</a></li>
                            <li><a class="dropdown-item" href="{{ route('correos-encuesta', ['id' => $id]) }}"><i class="bi bi-envelope me-2"></i> Configurar Correos</a></li>
                        </ul>
                    </div>

                    <form action="{{ route($esEncuesta ? 'eliminar-encuesta' : 'eliminar-plantilla', ['id' => $id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger rounded-circle" onclick="return confirm('¿Eliminar esta encuesta?')" title="Eliminar">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-guinda { background-color: #9d241d !important; }
    .text-guinda { color: #9d241d !important; }
    .btn-guinda { 
        background-color: #9d241d; 
        color: white; 
        border: none;
    }
    .btn-guinda:hover { 
        background-color: #7a1c17; 
        color: white; 
    }
    .card-hover { transition: transform 0.2s, box-shadow 0.2s; }
    .card-hover:hover { 
        transform: translateY(-5px); 
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; 
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>