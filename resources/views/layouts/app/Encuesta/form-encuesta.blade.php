@extends('layouts.app')

@section('title')
    {{ $encuesta ? 'Edición' : 'Creación' }} de {{ $esEncuesta ? 'plantilla' : 'encuesta' }}
@endsection

@section('content')

    @if ($mostrarBarraProgreso)
        <x-barra-progreso pasoActual="datos" :encuestaId="$encuesta->id ?? null" :mostrarBarraProgreso="$mostrarBarraProgreso"/>
    @endif

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card border-1 rounded-4">
                    <div class="card-header bg-primary text-white p-4 rounded-top-4">
                        <h4 class="mb-0 fw-bold"><i class="bi bi-journal-plus me-2"></i>{{ $encuesta ? 'Editar' : 'Nueva' }} {{ $esEncuesta ? 'Encuesta' : 'Plantilla' }}</h4>
                        <p class="mb-0 opacity-75"> {{ $encuesta ? 'Modifica los datos de la ' . ($esEncuesta ? 'encuesta' : 'plantilla') : 'Completa los datos para crear la ' . ($esEncuesta ? 'encuesta' : 'plantilla') }}</p>
                    </div>

                    <div class="card-body p-4">
                        <div id="form-error-alert" class="alert alert-danger d-none border-0 shadow-sm rounded-3 mb-4">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-exclamation-octagon-fill fs-4 me-2"></i>
                                <div>
                                    <strong class="d-block">Error en el formulario</strong>
                                    <span id="form-error-message">Corrige los campos marcados en rojo antes de continuar</span>
                                </div>
                            </div>
                        </div>
                        @if(isset($encuesta))
                            <form action="{{ $esEncuesta ? route('actualizar-encuesta', ['id' => $encuesta->id]) : route('home') }}" method="post" id="create-form">
                            @method('PUT')
                        @else
                            <form action="{{ $esEncuesta ? route('crear-encuesta', ['grupo' => $grupo]) : route('home') }}" method="post" id="create-form">
                        @endif

                            @csrf
                            <input type="hidden" name="mostrarBarraProgreso" value="{{ $mostrarBarraProgreso }}">
                            <input type="hidden" name="id_grupo" id='id_grupo' value="{{ $encuesta->idGrupo?? $grupo }}">
                            <div class="mb-4">
                                <label for="titulo" class="form-label fw-bold text-secondary">Título de la Encuesta:</label>
                                <input type="text" class="form-control form-control-lg border-2 shadow-sm" 
                                    name="titulo" id="titulo" placeholder="Ej: Satisfacción de grupo" value="{{ $encuesta->titulo?? '' }}" required>
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
                                        <input type="datetime-local" class="form-control border-2 shadow-sm" name="fecha_inicio" id="fecha_inicio" value="{{ $encuesta?->fechaInicio?->format('Y-m-d\TH:i') ?? today()->addDays(1)->format('Y-m-d\TH:i') }}">
                                    </div>
                                    <small class="error-text d-block mt-1" id="fecha-inicio-text-error" style="color: red; font-weight: bold;"></small>
                                </div>
                                <div class="col-md-6">
                                    <label for="fecha_termino" class="form-label fw-bold text-secondary">Fecha de Término:</label>
                                    <div class="input-group">
                                        <span class="input-group-text border-2 bg-light"><i class="bi bi-calendar-check"></i></span>
                                        <input type="datetime-local" class="form-control border-2 shadow-sm" name="fecha_termino" id="fecha_termino" value="{{ $encuesta?->fechaTermino?->format('Y-m-d\TH:i') ?? today()->addWeeks(1)->format('Y-m-d\TH:i') }}">
                                    </div>
                                    <small class="error-text d-block mt-1" id="fecha-termino-text-error" style="color: red; font-weight: bold;"></small>
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
                                <small class="error-text d-block mt-1" id="estilo-text-error" style="color: red; font-weight: bold;"></small>
                            </div>

                            <div class="d-grid mt-5">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold">
                                    <i class="bi bi-check-circle me-2"></i>{{ $encuesta ? 'Actualizar ' . ($esEncuesta ? 'Encuesta' : 'Plantilla') : 'Crear ' . ($esEncuesta ? 'Encuesta' : 'Plantilla') }}
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
            let estiloTextError = document.getElementById('estilo-text-error');
            estiloTextError.innerHTML = '';
        }

        /**
         * Evento que verifica que la fecha de inicio no sea en el pasado
         */
        document.getElementById('fecha_inicio').addEventListener('change', function(event) {
            let fechaInicio = event.target;
            let fecha = new Date(fechaInicio.value);
            let ahora = new Date();
            ahora.setSeconds(0,0);
            let textError = document.getElementById('fecha-inicio-text-error');
            if (fecha <= ahora) {
                addTextError(fechaInicio, textError, 'No se puede crear una encuesta en el pasado');
            } else {
                removeTextError(fechaInicio, textError);
            }
            let fechaTermino = document.getElementById('fecha_termino');
            verifyFechaTermino(fechaInicio, fechaTermino);
        });

        /**
         * Evento que verifica que el la fecha de termino sea posterior a la fecha de inicio
         */
        document.getElementById('fecha_termino').addEventListener('change', function(event) {
            let fechaTermino = event.target;
            let fechaInicio = document.getElementById('fecha_inicio');
            verifyFechaTermino(fechaInicio, fechaTermino);
        });

        function verifyFechaTermino(fechaInicio, fechaTermino) {
            let fechaInputString = new Date(fechaInicio.value);
            let fechaTerminoString = new Date(fechaTermino.value);
            let textError = document.getElementById('fecha-termino-text-error');
            if (fechaInputString >= fechaTerminoString) {
                addTextError(fechaTermino, textError, 'La fecha de termino debe ser posterior a la de inicio');
            } else {
                removeTextError(fechaTermino, textError);
            }
        }

        function addTextError(input, textError, message) {
            input.classList.add('is-invalid');
            textError.innerHTML = message;
        }

        function removeTextError(input, textError) {
            input.classList.remove('is-invalid');
            textError.innerHTML = ' ';
        }

        // Evento que verifica que todos los campos necesarios para la encuesta esten presentes y no tengan ningún error
        document.getElementById('create-form').addEventListener('submit', function(event) {
            let fechaInicio = document.getElementById('fecha_inicio');
            let fechaTermino = document.getElementById('fecha_termino');
            if (fechaInicio.classList.contains('is-invalid') || fechaTermino.classList.contains('is-invalid')) {
                if (fechaInicio.classList.contains('is-invalid') && fechaTermino.classList.contains('is-invalid')) {
                    addFormErrorMessage(event, 'Las fechas de inicio y de término son invalidas');
                } else if (fechaInicio.classList.contains('is-invalid')) {
                    addFormErrorMessage(event, 'La fecha de inicio es inválida (no puede ser en el pasado)');
                } else {
                    addFormErrorMessage(event, 'La fecha de término debe ser posterior a la fecha de inicio');
                }
            } else if (!cardSeleccionada) {
                addFormErrorMessage(event, 'Debe elegir un estilo para la encuesta')
                let estiloTextError = document.getElementById('estilo-text-error');
                estiloTextError.innerHTML = 'No se puede crear una encuesta sin un estilo';
            } else {
                alertContainer.classList.add('d-none');
            }
        });

        function addFormErrorMessage(event, message) {
            event.preventDefault();

            let alertContainer = document.getElementById('form-error-alert');
            let alertMessage = document.getElementById('form-error-message');
            alertContainer.classList.remove('d-none');
            alertMessage.innerText = message;
            
            alertContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    </script>
@endsection