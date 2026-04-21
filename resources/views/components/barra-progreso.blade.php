@props([
    'pasoActual' => 'datos',
    'encuestaId' => null,
])

@php
    $pasos = [
        'datos' => ['label' => 'Datos',
                    'icon' => 'bi-clipboard-check',
                    'ruta' => 'editar-encuesta'],
        'preguntas' => ['label' => 'Preguntas',
                        'icon' => 'bi-question-circle',
                        'ruta' => 'form-preguntas-encuesta'],
        'correos' => ['label' => 'Correos',
                      'icon' => 'bi-envelope',
                      'ruta' => 'correos-encuesta']
    ];
    
    $orden = array_keys($pasos);
    $indiceActual = array_search($pasoActual, $orden);
@endphp

<div class="barra-progreso bg-white border-bottom py-3 mb-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center position-relative">
            
            <!-- Línea conectora -->
            <div class="progress-line position-absolute" 
                 style="left: 0; right: 0; top: 50%; height: 2px; z-index: 0;">
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
                        <a href="{{ route($paso['ruta'], $encuestaId) }}" class="paso-icon {{ $esActual ? 'current' : 'completed' }}">
                    @else
                        <div class="paso-icon {{ $esActual ? 'current' : ($estaPendiente ? 'pending' : 'completed') }}"> 
                    @endif

                    @if($estaCompleto)
                        <i class="bi {{ $paso['icon'] }}"></i>
                    @else
                        <span class="paso-number">{{ $indicePaso + 1 }}</span>
                    @endif

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
.barra-progreso .paso-icon.current {
    background: #0d6efd;
    color: white;
    box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.25);
}
.barra-progreso .paso-icon.completed {
    background: #198754;
    color: white;
}
.barra-progreso .paso-icon.pending {
    background: #e9ecef;
    color: #6c757d;
}
.barra-progreso .paso-icon:hover {
    transform: scale(1.1);
}
</style>