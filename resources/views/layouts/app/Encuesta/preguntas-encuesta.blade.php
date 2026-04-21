@extends('layouts.app')

@section('title')
    Edición de encuesta
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ asset('estilos/' . $encuesta->estilo . '.css') }}">
@endsection

@section('content')

<x-barra-progreso pasoActual="preguntas" :encuestaId="$encuesta->id" />

<!-- Información de la encuesta -->
<div class="container py-5" style="max-width: 1000px;">
    <div class="info-questio mb-5 shadow-sm rounded-4 border bg-white" style="border-color:grey !important;">
        <div class="info-question-body p-4">
            <h1 class="display-6 fw-bold">{{ $encuesta->titulo }}</h1>
            @if ($encuesta->descripcion !== null)
                <p class="lead text-secondary">{{ $encuesta->descripcion }}</p>
            @endif
            @if ($encuesta->textoAdvertencia !== null)
                <div class="texto-advertencia mb-3">
                    <p class="lead text-secondary">{{ $encuesta->textoAdvertencia }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Preguntas -->
    <div class="d-grid gap-4" style="">
        @foreach ($encuesta->preguntas as $pregunta)
            @if ($pregunta->idTipoPregunta == 1)
                <x-preguntas.matriz :pregunta="$pregunta" :disabled="true" />
            @elseif ($pregunta->idTipoPregunta == 2)
                <x-preguntas.abierta :pregunta="$pregunta" :disabled="true" />
            @elseif ($pregunta->idTipoPregunta == 3)
                <x-preguntas.opcion-multiple :pregunta="$pregunta" :disabled="true" />
            @endif
        @endforeach

        <div class="d-flex gap-3 align-items-center flex-wrap mt-2">
            <div class="d-grid" style="min-width: 260px; flex: 1;">
                <button class="btn btn-outline-primary btn-lg rounded-pill"
                        data-bs-toggle="modal" data-bs-target="#crear-modal">
                    <i class="bi bi-plus-lg me-2"></i> Agregar pregunta
                </button>
            </div>
            <div class="d-grid" style="min-width: 260px; flex: 1;">
                <a href="{{ route('correos-encuesta', ['id' => $encuesta->id]) }}" class="btn btn-primary btn-lg rounded-pill">
                    Finalizar preguntas <i class="bi bi-check-lg ms-2"></i>
                </a>
            </div>
        </div>

    <!-- Modal Editar Pregunta -->
    <div class="modal fade shadow border-0" id="edit-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Editar pregunta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <form id="forms-edit" action="{{ route('editar-pregunta', ['id' => 'ID_PREGUNTA']) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @if ($esEncuesta)
                            <input type="hidden" name="id_encuesta" value="{{ $encuesta->id }}">
                        @else
                            <input type="hidden" name="id_encuesta_plantilla" value="{{ $encuesta->id }}">
                        @endif
                        <input type="hidden" name="orden" id='orden' value="">
                        <input type="hidden" name="id_pregunta" id='id_pregunta' value="">

                        <div class="mb-3">
                            <label class="form-label fw-medium small text-secondary">Tipo de pregunta:</label>
                            <select class="form-select bg-light border-0" name="id_tipo_pregunta" id="id_tipo_pregunta-editar">
                                @foreach($tiposPregunta as $tipo)
                                    <option value={{$tipo->idTipoPregunta}}>{{$tipo->nombre}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium small text-secondary">Texto de la pregunta:</label>
                            <textarea id="editar-texto" name="texto" class="form-control" placeholder="Escribe aquí la pregunta" required></textarea>
                        </div>

                        <div id="info-pregunta">
                            
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary px-4">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Crear Pregunta-->
    <div class="modal fade shadow border-0" id="crear-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Crear pregunta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">

                    <form action="{{ route('crear-pregunta') }}" method="post">
                        @csrf
                        @if ($esEncuesta)
                            <input type="hidden" name="id_encuesta" value="{{ $encuesta->id }}">
                        @else
                            <input type="hidden" name="id_encuesta_plantilla" value="{{ $encuesta->id }}">
                        @endif
                        <input type="hidden" name="orden" value=" {{ count($encuesta->preguntas) + 1 }} ">

                        <div class="mb-3">
                            <label class="form-label fw-medium small text-secondary">Tipo de pregunta:</label>
                            <select class="form-select bg-light border-0" name="id_tipo_pregunta" id="id_tipo_pregunta-crear">
                                @foreach($tiposPregunta as $tipo)
                                    <option value={{$tipo->idTipoPregunta}}>{{$tipo->nombre}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium small text-secondary">Texto de la pregunta:</label>
                            <textarea name="texto" class="form-control" id="crear-modal-texto" placeholder="Escribe aquí la pregunta" required></textarea>
                        </div>

                        <div id="campos-pregunta">

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary px-4" id="boton-agregar">Agregar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

    <script>
        const contadores = {
            opciones: 0,
            filas: 0,
            columnas: 0
        };

        const mensajeAlertOpciones= "Debe haber al menos dos opción.";
        const mensajeAlertFilas= "Debe haber al menos una filas.";
        const mensajeAlertColumnas= "Debe haber al menos dos columna.";

        const minOpciones = 2;
        const minFilas = 1;
        const minColumnas = 2;

        const baseOpcion = `<div class= "mb-3">
                                <label class="form-label fw-medium small text-secondary">Opciones de respuesta:</label>
                                <div id="opciones-crear" class="mb-3">
                                </div>
                                <button type="button" class="btn btn-primary" id="boton-agregar-opcion">Añadir opción</button>
                            </div>
                            <div class="row g-2 mt-2">
                                <div class="col-6">
                                    <label class="form-label fw-medium small text-secondary">Mínima selección</label>
                                    <input type="number" class="form-control form-control-sm" id="min-seleccion" name="min_seleccion" min="1" value="1" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-medium small text-secondary">Máxima selección</label>
                                    <input type="number" class="form-control form-control-sm" id="max-seleccion" name="max_seleccion" min="1" value="1" required>
                                </div>
                            </div>`;

        const baseMatriz = `<div class="row g-4 mt-1">
                                <div class= "col-6 border-end">
                                    <label class="form-label fw-medium small text-secondary">Columnas de la matriz:</label>
                                    <div id="columnas-crear" class="mb-3">
                                    </div>
                                    <button type="button" class="btn btn-primary" id="boton-agregar-columnas">Añadir columna</button>
                                </div>
                                <div class= "col-6">
                                    <label class="form-label fw-medium small text-secondary">Filas de la matriz:</label>
                                    <div id="filas-crear" class="mb-3">
                                    </div>
                                    <button type="button" class="btn btn-primary" id="boton-agregar-filas">Añadir fila</button>
                                </div>
                            </div>`;

        function resetContadores() {
            contadores.columnas = 0;
            contadores.filas = 0;
            contadores.opciones = 0;
        }

        function getPreguntaById(id){
            let preguntas = @json($encuesta->preguntas);
            return preguntas.find(p => p.idPregunta == id);
        }

        /**
         * Evento que se ejecuta al seleccionar un nuevo tipo de pregunta en el modal de edición de pregunta
         * Si se selecciona el mismo tipo de la pregunta que ya tenía, se mantiene los componentes de la pregunta
         * En caso contrario, se eliminan los componentes en el modal y se agregan los componentes bases del nuevo tipo de pregunta
         */
        document.getElementById('id_tipo_pregunta-editar').addEventListener('change', function(){
            resetContadores();
            let id = document.getElementById('id_pregunta').value;
            let pregunta = getPreguntaById(id);

            let preguntaSeleccionada = parseInt(this.value);
            let contenedor = document.getElementById('info-pregunta');
            if (pregunta.idTipoPregunta == preguntaSeleccionada) {
                addInfoComponentes(pregunta, preguntaSeleccionada, contenedor);
            } else {
                contenedor.innerHTML = "";
                addBaseComponentesPregunta(contenedor, preguntaSeleccionada);
            }
        });

        /**
         * Evento que se ejecuta al abrir el modal de edición de pregunta
         * Coloca la información de la pregunta a editar en el modal
         */
        document.getElementById('edit-modal').addEventListener('show.bs.modal', function (event) {
            resetContadores();

            let button = event.relatedTarget;
            let id = button.getAttribute('data-bs-id');
            // Se agrega el id correspondiente de la pregunta que se está editando
            let forms = document.getElementById('forms-edit');
            forms.action = forms.action.replace('ID_PREGUNTA', id);

            let pregunta = getPreguntaById(id);
            // Se agrega la información de la pregunta
            document.getElementById('id_pregunta').value = pregunta.idPregunta;
            document.getElementById('orden').value = pregunta.orden;
            document.getElementById('id_tipo_pregunta-editar').value = pregunta.idTipoPregunta;
            document.getElementById('editar-texto').value = pregunta.texto;

            let tipo = pregunta.idTipoPregunta;
            let contenedor = document.getElementById('info-pregunta');
            addInfoComponentes(pregunta, tipo, contenedor);
        });

        /**
         * Agrega las opciones, filas o columnas de una pregunta dependiendo del tipo de pregunta que sea.
         * @param {Object} pregunta - La pregunta de la cual se quieren agregar los componentes.
         * @param {Number} tipo - El tipo de pregunta que se está editando.
         * @param {HTMLElement} contenedor - El contenedor donde se agregarán los componentes.
         */
        function addInfoComponentes(pregunta, tipo, contenedor) {
            contenedor.innerHTML = "";
            switch (tipo) {
                // Matriz
                case 1:
                    contenedor.innerHTML += baseMatriz;
                    pregunta.filasMatriz.forEach(fila => {
                        addComponente(fila, 'fila', 'filas', 'Fila', minFilas, mensajeAlertFilas);
                    });
                    document.getElementById('boton-agregar-filas').addEventListener('click', function() {
                        addComponente(null, 'fila', 'filas', 'Fila', minFilas, mensajeAlertFilas);
                    });

                    pregunta.columnasMatriz.forEach(columna => {
                        addComponente(columna, 'columna', 'columnas', 'Columna', minColumnas, mensajeAlertColumnas);
                    });
                    document.getElementById('boton-agregar-columnas').addEventListener('click', function() {
                        addComponente(null, 'columna', 'columnas', 'Columna', minColumnas, mensajeAlertColumnas);
                    });
                    break;
                // Opcion Multiple
                case 3:
                    contenedor.innerHTML += baseOpcion;
                    
                    pregunta.opciones.forEach(opcion => {
                        addComponente(opcion, 'opcion', 'opciones', 'Opción', minOpciones, mensajeAlertOpciones);
                    });
                    document.getElementById('min-seleccion').value = pregunta.minSeleccion;
                    document.getElementById('max-seleccion').value = pregunta.maxSeleccion;
                    
                    document.getElementById('boton-agregar-opcion').addEventListener('click', function() {
                        addComponente(null, 'opcion', 'opciones', 'Opción', minOpciones, mensajeAlertOpciones);
                    });
                    break;
                default:
                    break;
            }
        }

        /**
         * Agrega un nuevo componente (opcion, fila o columna) en los modals
         * @param {Object|null} componente - El componente a agregar, si es null se agrega un componente vacío, de caso contrario se agrega el componente cocn la información que tenga el objeto
         * @param {String} tipo - El nombre del componente a agregar
         * @param {String} nombreArr - El nombre del arreglo que se envía en el formulario
         * @param {String} placeholder - El placeholder que tendrá
         * @param {Number} minNumComponentes - El número mínimo de componentes que debe de tener la pregunta
         * @param {String} mensajeAlert - El mensaje que se muestra en caso de que se intente eliminar un componente cuando ya tiene el mínimo de componentes
         */
        function addComponente(componente=null, tipo, nombreArr, placeholder, minNumComponentes, mensajeAlert){
            contadores[nombreArr]++;
            let contenedor = document.getElementById(`${nombreArr}-crear`);
            let nuevoComponente = "";
            if (componente != null) {
                nuevoComponente = `<div class="input-group mb-3" id="${tipo}-${componente.orden}">
                                    <input type="hidden" name="${nombreArr}[${componente.orden-1}][id_${tipo}]" value="${componente.id}">
                                    <input type="hidden" name="${nombreArr}[${componente.orden-1}][id_pregunta]" value="${componente.idPregunta}">
                                    <input type="hidden" name="${nombreArr}[${componente.orden-1}][orden]" value="${componente.orden}">
                                    <input type="text" name="${nombreArr}[${componente.orden-1}][texto]" class="form-control" placeholder="${placeholder} ${componente.orden}" value="${componente.texto}" required>
                                    <button type="button" class="btn btn-danger" onclick="deleteComponente(${componente.orden}, '${tipo}', '${nombreArr}', ${minNumComponentes}, '${mensajeAlert}', '${placeholder}')">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </div>`;
            } else {
                nuevoComponente = `<div class="input-group mb-3" id="${tipo}-${contadores[nombreArr]}">
                                    <input type="text" name="${nombreArr}[${contadores[nombreArr]-1}][texto]" class="form-control" placeholder="${placeholder} ${contadores[nombreArr]}" required>
                                    <button type="button" class="btn btn-danger" onclick="deleteComponente(${contadores[nombreArr]}, '${tipo}', '${nombreArr}', ${minNumComponentes}, '${mensajeAlert}', '${placeholder}')">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </div>`;
            }
            contenedor.insertAdjacentHTML('beforeend', nuevoComponente);
        }

        /**
         * Elimina un componente (opcion, fila o columna) en los modals
         * @param {Number} orden - El orden del componente a eliminar
         * @param {String} tipo - El nombre del componente a agregar
         * @param {String} nombreArr - El nombre del arreglo que se envía en el formulario
         * @param {Number} minNumComponentes - El número mínimo de componentes que debe de tener la pregunta
         * @param {String} mensajeAlert - El mensaje que se muestra en caso de que se intente eliminar un componente cuando ya tiene el mínimo de componentes
         * @param {String} placeholder - El placeholder que tendrá
         */
        function deleteComponente(orden, tipo, nombreArr, minNumComponentes, mensajeAlert, placeholder) {
            let contenedor = document.getElementById(`${nombreArr}-crear`);
            let totalComponentes = contenedor.querySelectorAll(`[id^="${tipo}-"]`).length;
            if (totalComponentes <= minNumComponentes) {
                alert(mensajeAlert);
                return;
            }
            let componente = document.getElementById(`${tipo}-${orden}`);
            if (componente) componente.remove();
            let componentesRestantes = contenedor.querySelectorAll(`[id^="${tipo}-"]`);
            contadores[nombreArr] = componentesRestantes.length;
            // Se actualiza el orden de los componentes restantes
            componentesRestantes.forEach((div, index) => {
                let nuevoNum = index + 1;
                div.id = `${tipo}-${nuevoNum}`;
                let inputs = div.querySelectorAll('input');
                // Se actualizan los inputs con el nuevo orden
                inputs.forEach(input => {
                    if (input.name.includes('texto')) {
                        input.name = `${nombreArr}[${index}][texto]`;
                        input.placeholder=`${placeholder} ${nuevoNum}`;
                    } else {
                        if (input.name.includes('id_${tipo}')) {
                            input.name = `${nombreArr}[${index}][id_${tipo}]`;
                        } else {
                            if (input.name.includes('id_pregunta')) {
                                input.name = `${nombreArr}[${index}][id_pregunta]`;
                            } else {
                                if (input.name.includes('orden')) {
                                    input.name = `${nombreArr}[${index}][orden]`;
                                    input.value= nuevoNum;
                                }
                            }
                        } 
                    }
                });
                // Se actualiza el paramatro del boton de eliminar
                let boton = div.querySelector('button');
                if (boton) {
                    boton.setAttribute('onclick', `deleteComponente(${nuevoNum}, '${tipo}', '${nombreArr}', ${minNumComponentes}, '${mensajeAlert}', '${placeholder}')`);
                }
            });
        }

        /**
         * Evento que se ejecuta al abrir el modal de creación de pregunta
         * Asigna una pregunta abierta como predeterminada y limpia los campos de componentes
         */
        document.getElementById('crear-modal').addEventListener('show.bs.modal', function () {
            resetContadores();
            document.getElementById('id_tipo_pregunta-crear').value = 2;// Se selecciona pregunta abierta por defecto
            document.getElementById('campos-pregunta').innerHTML = ""; // Se limpia el campo de los componentes(opciones, filas o columnas)
            document.getElementById('crear-modal-texto').value = "";
        });

        /**
         * Evento que se ejecuta al seleccionar un nuevo tipo de pregunta en el modal de creación de pregunta
         * Agrega los comopnentes base del tipo de pregunta seleccionadp
         */
        document.getElementById('id_tipo_pregunta-crear').addEventListener('change', function() {    
            resetContadores();
            document.getElementById('campos-pregunta').innerHTML = ""; // Se limpia el campo de los componentes(opciones, filas o columnas)

            let preguntaSeleccionada = parseInt(this.value);
            let contenedor = document.getElementById('campos-pregunta');
            contenedor.innerHTML = "";
            
            addBaseComponentesPregunta(contenedor, preguntaSeleccionada);
        });

        /**
         * Agrega los componentes bases de una pregunta dependiendo de su tipo
         */
        function addBaseComponentesPregunta(contenedor, preguntaSeleccionada) {
            switch (preguntaSeleccionada) {
                // Matriz
                case 1:
                    contenedor.innerHTML += baseMatriz;
                    for (let index = 0; index < minFilas; index++) {
                        addComponente(null, 'fila', 'filas', 'Fila', minFilas, mensajeAlertFilas);
                    }
                    document.getElementById('boton-agregar-filas').addEventListener('click', function() {
                        addComponente(null, 'fila', 'filas', 'Fila', minFilas, mensajeAlertFilas);
                    });

                    for (let index = 0; index < minColumnas; index++) {
                        addComponente(null, 'columna', 'columnas', 'Columna', minColumnas, mensajeAlertColumnas);
                    }
                    document.getElementById('boton-agregar-columnas').addEventListener('click', function() {
                        addComponente(null, 'columna', 'columnas', 'Columna', minColumnas, mensajeAlertColumnas);
                    });
                    break;
                // Opción Multiple
                case 3:
                    contenedor.innerHTML += baseOpcion;
                    for (let index = 0; index < minOpciones; index++) {
                        addComponente(null, 'opcion', 'opciones', 'Opción', minOpciones, mensajeAlertOpciones);   
                    }
                    document.getElementById('boton-agregar-opcion').addEventListener('click', function() {
                        addComponente(null, 'opcion', 'opciones', 'Opción', minOpciones, mensajeAlertOpciones);
                    });
                    break;
                default:
                    break;
            }
        }
    </script>
@endsection