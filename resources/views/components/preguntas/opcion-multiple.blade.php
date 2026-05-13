@props([
    'pregunta' => null,
    'disabled' => false,
])

<div class="card card-question shadow-sm rounded-4 border-1 bg-white mb-6" style="border-color:grey;">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <span class="text-uppercase fw-bold text-muted small letter-spacing-1">Pregunta #{{$pregunta->orden}}</span>
            <div class="d-flex gap-1">
                @if ($disabled)
                    <x-preguntas.boton-edicion :pregunta="$pregunta"/>
                    <x-preguntas.boton-eliminar :pregunta="$pregunta"/>
                @endif
            </div>
        </div>   

        <p class="fw-semibold mb-3">{!! nl2br(e($pregunta->texto)) !!}</p>
        
        @foreach ($pregunta->opciones as $opcion)
            <div class="form-check" id="opcion-multiple{{ $pregunta->idPregunta }}">
                @if ($pregunta->maxSeleccion == 1)
                    <input
                    class="form-check-input"
                    type="radio"
                    name="respuestas[{{ $pregunta->orden-1 }}]"
                    @if ($disabled)
                        disabled
                    @endif>
                @else
                    <input class="form-check-input-checkbox"
                    type="checkbox"
                    name="respuestas[{{ $pregunta->orden-1 }}]"
                    @if ($disabled)
                        disabled
                    @endif>
                @endif
                <label class="form-check-label" >
                    {{ $opcion->texto }}
                </label>
            </div>
        @endforeach
    </div>
</div>