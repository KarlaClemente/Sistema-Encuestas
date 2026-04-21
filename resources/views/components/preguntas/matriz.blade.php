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

        <p class="fw-semibold mb-3">{{ $pregunta->texto }}</p>
        
        <div class="table-responsive">
            <table class="table table-striped align-middle table-matriz">
                <colgroup>
                    <col class="col-label">
                    @foreach ($pregunta->columnasMatriz as $columna)
                        <col class="col-option">
                    @endforeach
                </colgroup>
                <thead class="table-light">
                    <tr>
                        <th scope="col"></th>
                            @foreach ($pregunta->columnasMatriz as $columna)
                                <th scope="col" class="text-center fw-normal small">
                                    {{ $columna->texto }}
                                </th>
                            @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pregunta->filasMatriz as $fila)
                        <tr>
                            <th scope="row" class="fw-normal">{{ $fila->texto }}</th>
                            @foreach ($pregunta->columnasMatriz as $columna)
                                <td class="text-center">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="respuestas[{{ $pregunta->orden - 1 }}][{{ $fila->id }}]"
                                        value="{{ $columna->id }}"
                                        @if ($disabled) disabled @endif
                                    >
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
  .table-matriz {
    table-layout: fixed;
    width: 100%;
  }
  .table-matriz th,
  .table-matriz td {
    word-wrap: break-word;
    overflow-wrap: break-word;
  }
  .col-label { width: 20%; }
  .col-option { width: calc(80% / {{ count($pregunta->columnasMatriz) }}); }
</style>