@extends('layouts.app')

@section('title')
    Configuración de correos
@endsection

@section('content')

    @if ($mostrarBarraProgreso)
        <x-barra-progreso pasoActual="correos" :encuestaId="$encuesta->id ?? null" :mostrarBarraProgreso="$mostrarBarraProgreso"/>
    @endif

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 ">
                <div class="card border-1 rounded-4">
                    <div class="card-header bg-primary text-white p-4 rounded-top-4">
                        <h4 class="mb-0 fw-bold"><i class="bi bi-journal-plus me-2"></i>Configuración de correos</h4>
                        <p class="mb-0 opacity-75">
                            Personaliza el asunto y cuerpo de los correos para la encuesta
                            <span class="fw-bold">{{$encuesta->titulo}}</span>
                        </p>
                    </div>

                    <div class="nav nav-pills nav-fill bg-light p-1 rounded-pill mt-4 border">
                        <button class="nav-link active rounded-pill" data-bs-toggle="tab" data-bs-target="#tab-invitacion">
                            <i class="bi bi-envelope me-2"></i>Invitación
                        </button>
                        <button class="nav-link rounded-pill" data-bs-toggle="tab" data-bs-target="#tab-recordatorio">
                            <i class="bi bi-alarm me-2"></i>Recordatorio
                        </button>
                        <button class="nav-link rounded-pill" data-bs-toggle="tab" data-bs-target="#tab-completado">
                            <i class="bi bi-check-circle me-2"></i>Completado
                        </button>
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

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="tab-invitacion">
                                <x-correo.tab-pane
                                :esEncuesta="$esEncuesta"
                                :idPlantilla="$plantillas[0]->idPlantilla"
                                :tipo="$plantillas[0]->tipo"
                                :asunto="$plantillas[0]->asunto"
                                :cuerpo="$plantillas[0]->cuerpo"
                                descripcion="Se envia como primer correo para la realización de la encuesta"/>
                            </div>

                            <div class="tab-pane fade" id="tab-recordatorio">
                                <x-correo.tab-pane
                                    :esEncuesta="$esEncuesta"
                                    :idPlantilla="$plantillas[1]->idPlantilla"
                                    :tipo="$plantillas[1]->tipo"
                                    :asunto="$plantillas[1]->asunto"
                                    :cuerpo="$plantillas[1]->cuerpo"
                                    descripcion="Se envia como recordatorio para que se conteste la encuesta"/>
                            </div>
                            
                            <div class="tab-pane fade" id="tab-completado">
                                <x-correo.tab-pane
                                    :esEncuesta="$esEncuesta"
                                    :idPlantilla="$plantillas[2]->idPlantilla"
                                    :tipo="$plantillas[2]->tipo"
                                    :asunto="$plantillas[2]->asunto"
                                    :cuerpo="$plantillas[2]->cuerpo"
                                    descripcion="Se envia como recordatorio para que se conteste la encuesta"/>
                            </div>

                            <div class="d-grid mt-5">
                                <a href="{{ route('home') }}" class="btn btn-primary btn-lg rounded-pill fw-bold">
                                    <i class="bi bi-check-circle me-2"></i>Finalizar configuración
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                let container = document.getElementById('toastClipboard');
                document.getElementById('toastClipboardMessage').textContent = 'Se copio la variable';
                showToast(container);
            });
        }
    </script>
@endsection