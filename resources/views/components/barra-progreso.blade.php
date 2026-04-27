@props([
    'pasoActual' => 'datos',
    'encuestaId' => null,
    'mostrarBarraProgreso' => false,
])

@php
    $pasos = [
        'datos' => ['label' => 'Datos',
                    'ruta' => 'editar-encuesta'],
        'preguntas' => ['label' => 'Preguntas',
                        'ruta' => 'form-preguntas-encuesta'],
        'correos' => ['label' => 'Correos',
                      'ruta' => 'correos-encuesta']
    ];
    
    $orden = array_keys($pasos);
    $indiceActual = array_search($pasoActual, $orden);
@endphp

<div class="barra-progreso bg-white border-bottom py-3 mb-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center position-relative">
            
            <!-- Línea conectora -->
            <div class="progress-line position-absolute">
            </div>
            
            @foreach($pasos as $key => $paso)
                @php
                    $indicePaso = array_search($key, $orden);
                    $estaCompleto = $indicePaso < $indiceActual;
                    $esActual = $key === $pasoActual;
                    // Solo se puede navegar a pasos anteriores o actual si la encuesta ya existe
                    $esClickeable = $encuestaId && ($estaCompleto || $esActual);
                    $estaPendiente = $indicePaso > $indiceActual;
                @endphp
                
                <div class="paso-wrapper text-center position-relative z-1">
                    @if($esClickeable)
                        <a href="{{ route($paso['ruta'], ['id' => $encuestaId, 'mostrarBarraProgreso' => $mostrarBarraProgreso]) }}" class="paso-icon {{ $esActual ? 'actual' : 'completado' }}">
                    @else
                        <div class="paso-icon {{ $esActual ? 'actual' : ($estaPendiente ? 'pendiente' : 'completado') }}"> 
                    @endif

                    <span class="paso-number">{{ $indicePaso + 1 }}</span>

                    @if($esClickeable)
                        </a>
                    @else
                        </div>
                    @endif
                    <div class="paso-label mt-2 {{ $esActual ? 'fw-bold text-primary' : ($estaCompleto ? 'text-success' : 'text-muted') }}">
                        {{ $paso['label'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
.barra-progreso .paso-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    transition: all 0.3s ease;
    text-decoration: none;
}
.barra-progreso .paso-icon.actual {
    background: #0d6efd;
    color: white;
    box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.25);
}
.barra-progreso .paso-icon.completado {
    background: #198754;
    color: white;
}
.barra-progreso .paso-icon.pendiente {
    background: #e9ecef;
    color: #6c757d;
}
.barra-progreso .paso-icon:hover {
    transform: scale(1.1);
}
.progress-line{
    left: 0;
    right: 10px;
    top: 25%;
    height: 2px;
    z-index: 0;
    background-color: #e9ecef;
}
</style>