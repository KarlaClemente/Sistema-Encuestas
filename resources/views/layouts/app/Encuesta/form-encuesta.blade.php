@extends('layouts.app')

@section('title')
    {{ $encuesta ? 'Edición' : 'Creación' }} de encuesta
@endsection

@section('content')

    <x-barra-progreso pasoActual="datos" :encuestaId="$encuesta->id ?? null" />

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card border-1 rounded-4">
                    <div class="card-header bg-primary text-white p-4 rounded-top-4">
                        <h4 class="mb-0 fw-bold"><i class="bi bi-journal-plus me-2"></i>{{ $encuesta ? 'Editar' : 'Nueva' }} Encuesta</h4>
                        <p class="mb-0 opacity-75"> {{ $encuesta ? 'Modifica los datos de la encuesta' : 'Completa los datos para crear la encuesta' }}</p>
                    </div>

                    <div class="card-body p-4">
                        @if(isset($encuesta))
                            <form action="{{ route('actualizar-encuesta', ['id' => $encuesta->id]) }}" method="post">
                            @method('PUT')
                        @else
                            <form action="{{ route('crear-encuesta', ['grupo' => $grupo]) }}" method="post">
                        @endif

                            @csrf
                            <input type="hidden" name="id_grupo" id='id_grupo' value="{{ $encuesta->idGrupo?? $grupo }}">
                            <div class="mb-4">
                                <label for="titulo" class="form-label fw-bold text-secondary">Título de la Encuesta:</label>
                                <input type="text" class="form-control form-control-lg border-2 shadow-sm" 
                                    name="titulo" id="titulo" placeholder="Ej: Satisfacción del Cliente 2026" value="{{ $encuesta->titulo?? '' }}" required>
                            </div>

                            <div class="mb-4">
                                <label for="descripcion" class="form-label fw-bold text-secondary">Descripción (Opcional):</label>
                                <textarea class="form-control border-2 shadow-sm" name="descripcion" 
                                        id="descripcion" rows="3" placeholder="Escribe el propósito de esta encuesta..."> {{ $encuesta->descripcion?? '' }} </textarea>
                            </div>

                            <div class="mb-4">
                                <label for="texto_advertencia" class="form-label fw-bold text-secondary">Texto de Advertencia (Opcional):</label>
                                <textarea class="form-control border-2 shadow-sm" name="texto_advertencia" 
                                        id="texto_advertencia" rows="3" placeholder="Escribe el texto de advertencia de esta encuesta..."> {{ $encuesta->textoAdvertencia?? '' }} </textarea>
                            </div>

                            <div class="mb-4">
                                    <label for="id_tipo_encuesta" class="form-label fw-bold text-secondary">Tipo de Encuesta:</label>
                                    <select class="form-select border-2 shadow-sm" name="id_tipo_encuesta" id="id_tipo_encuesta">
                                        <option selected disabled>Seleccionar...</option>
                                        @foreach ($tipoEncuesta as $tipo)
                                            <option value="{{$tipo->idTipoEncuesta}}"
                                            @if ($encuesta && $encuesta->idTipoEncuesta == $tipo->idTipoEncuesta)
                                                selected
                                            @endif>
                                                    {{$tipo->nombre}}
                                            </option>
                                        @endforeach
                                    </select>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="fecha_inicio" class="form-label fw-bold text-secondary">Fecha de Inicio:</label>
                                    <div class="input-group">
                                        <span class="input-group-text border-2 bg-light"><i class="bi bi-calendar-event"></i></span>
                                        <input type="datetime-local" class="form-control border-2 shadow-sm" name="fecha_inicio" id="fecha_inicio" value="{{ $encuesta?->fechaInicio?->format('Y-m-d\TH:i') ?? today()->format('Y-m-d\TH:i') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="fecha_termino" class="form-label fw-bold text-secondary">Fecha de Término:</label>
                                    <div class="input-group">
                                        <span class="input-group-text border-2 bg-light"><i class="bi bi-calendar-check"></i></span>
                                        <input type="datetime-local" class="form-control border-2 shadow-sm" name="fecha_termino" id="fecha_termino" value="{{ $encuesta?->fechaTermino?->format('Y-m-d\TH:i') ?? today()->addWeeks(1)->format('Y-m-d\TH:i') }}">
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="estilo" id="estilo" value="{{ $encuesta->estilo?? '' }}">
                            <div class="containerr">
                                <label class="form-label fw-bold text-secondary">Estilo:</label>
                                <div class="row g-3">
                                    @foreach ($estilos as $fila)
                                        @foreach ($fila as $estilo)
                                            <div class="col-6 col-md-4 col-lg-3">
                                                @include('estilos.cards', ['estilo' => $estilo])
                                            </div>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>

                            <div class="d-grid mt-5">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold">
                                    <i class="bi bi-check-circle me-2"></i>{{ $encuesta ? 'Actualizar Encuesta' : 'Crear Encuesta' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let cardSeleccionada = null;

        @if($encuesta)
            document.addEventListener('DOMContentLoaded', function() {
                selectEstilo('{{ $encuesta->estilo }}');
            });
        @endif


        function selectEstilo(estilo) {
            if (cardSeleccionada) {
                cardSeleccionada.classList.remove('border-primary', 'border-3');
            }
            document.getElementById('estilo').value = estilo;
            cardSeleccionada = document.getElementById(`estilo-${estilo}`);
            cardSeleccionada.classList.add('border-primary', 'border-3');
            cardSeleccionada.setAttribute('checked', true);
        }
    </script>
@endsection