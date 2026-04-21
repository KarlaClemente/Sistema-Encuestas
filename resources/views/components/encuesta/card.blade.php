@props([
    'titulo' => "",
    'descripcion' => "",
    'completada' => false,
    'fechaTermino' => "",
    'id' => null,
    'esEncuesta' => true,
])

<div class="col-md-6 col-lg-4">
    <div class="card h-100 border-primary">
        <div class="card-body">
            <h5 class="card-title">{{ $titulo }}</h5>
            <p class="card-text text-muted small">{{ $descripcion }}</p>
            <p class="mb-1"><small><strong>Estado:</strong>
            @if($completada)
                <span class="badge bg-success">Completada</span>
            @else
                <span class="badge bg-secondary">Pendiente</span>
            @endif
            </small></p>
            <p class="mb-0"><small><strong>Fecha término:</strong> {{ \Carbon\Carbon::parse($fechaTermino)->format('d/m/Y H:i') }}</small></p>
        </div>
        <div class="card-footer bg-transparent">
            <div class="d-flex gap-2">
                <a href="{{ route( $esEncuesta? 'editar-encuesta' : 'editar-plantilla', ['id' => $id]) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-pencil"></i> Editar
                </a>
                <form action="{{ route( $esEncuesta? 'eliminar-encuesta' : 'eliminar-plantilla', ['id' => $id]) }}"
                 method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar esta encuesta?')">
                        <i class="bi bi-trash"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>