@props([
    'esEncuesta' => true,
    'idPlantilla' => null,
    'tipo' => "",
    'asunto' => "",
    'cuerpo' => "",
    'descripcion' => '',
])

<div class="mb-1 mt-3">
    <form action="{{ $esEncuesta ? route('editar-correos-encuesta', ['id' => $idPlantilla]) : route('home') }}" method="post" id="update-form-{{ $tipo }}">
        @method('PUT')
        @csrf

        <div class="d-flex justify-content-between align-items-cente">
            <div>
                <h5 class="fw-bold mb-0">Correo de {{ $tipo }}</h5>
                <p class="text-muted small">{{ $descripcion }}</p>
            </div>
            <button type="submit" form="update-form-{{ $tipo }}" class="btn btn-dark btn-sm rounded-pill px-4 shadow-sm mt-2" style="background-color: #1a0a2a;">
                <i class="bi bi-save me-1"></i> Guardar
            </button>
        </div>

        <input type="hidden" name="id_plantilla" value="{{ $idPlantilla }}">
        <input type="hidden" name="tipo" value="{{ $tipo }}">

        <div class="mb-4">
            <label class="form-label fw-bold small">Asunto</label>
            <input type="text" class="form-control border-2 shadow-sm py-2" name="asunto" value="{{ $asunto }}" required>
        </div>
        <div class="mb-4">            
            <-- Barra de herramientas de Quill -->
            <div id="toolbar-{{ $tipo }}" class="ql-toolbar ql-snow">
                <span class="ql-formats">
                    <select class="ql-header">
                        <option value="1">Encabezado 1</option>
                        <option value="2">Encabezado 2</option>
                        <option value="3">Encabezado 3</option>
                        <option selected>Normal</option>
                    </select>
                </span>
                <span class="ql-formats">
                    <select class="ql-font">
                        <option selected>Sans Serif</option>
                        <option value="serif">Serif</option>
                        <option value="monospace">Monoespaciado</option>
                    </select>
                </span>
                <span class="ql-formats">
                    <select class="ql-size">
                        <option value="small">Pequeño</option>
                        <option selected>Normal</option>
                        <option value="large">Grande</option>
                        <option value="huge">Extragrande</option>
                    </select>
                </span>
                <span class="ql-formats">
                    <button class="ql-bold"></button>
                    <button class="ql-italic"></button>
                    <button class="ql-underline"></button>
                </span>
                <span class="ql-formats">
                    <button class="ql-link"></button>
                </span>
                <span class="ql-formats">
                    <select class="ql-align">
                        <option selected>Izquierda</option>
                        <option value="center">Centro</option>
                        <option value="right">Derecha</option>
                        <option value="justify">Justificado</option>
                    </select>
                    <button class="ql-list" value="ordered"></button>
                    <button class="ql-list" value="bullet"></button>
                    <button class="ql-indent" value="-1"></button>
                    <button class="ql-indent" value="+1"></button>
                    <button class="ql-clean"></button>
                </span>
            </div>

            <div id="editor-container-{{ $tipo }}" style="height: 200px;">
                {!! $cuerpo !!}
            </div>
            
            <input type="hidden" name="cuerpo" id="cuerpo-{{ $tipo }}">
        </div>
        <div class="card bg-light border-0 rounded-3">
            <div class="card-body p-3">
                <h6 class="fw-bold small mb-1">Variables disponibles</h6>
                <p class="text-muted extra-small mb-3" style="font-size: 0.75rem;">Haz clic para copiar e insertar en el asunto o cuerpo.</p>
                                        
                <div class="row g-2">
                    @php
                        $variables = [
                            ['tag' => '{{nombre_participante}}', 'desc' => 'Nombre del participante'],
                            ['tag' => '{{titulo_encuesta}}', 'desc' => 'Nombre de la encuesta'],
                            ['tag' => '{{enlace_encuesta}}', 'desc' => 'Enlace a la encuesta'],
                            ['tag' => '{{fecha_inicio}}', 'desc' => 'Fecha de inicio de la encuesta'],
                            ['tag' => '{{fecha_termino}}', 'desc' => 'Fecha de termino de la encuesta'],
                        ];
                    @endphp
                    @foreach($variables as $var)
                        <div class="col-12">
                            <div class="bg-white border rounded-3 p-2 d-flex flex-column cursor-pointer hover-shadow" style="cursor: pointer;" data-clip="{{ $var['tag'] }}" onclick="copyClipboard(this.dataset.clip)">
                                <code class="text-dark fw-bold">{{ $var['tag'] }}</code>
                                <small class="text-muted">{{ $var['desc'] }}</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    const quill_{!! $tipo !!} = new Quill('#editor-container-{{ $tipo }}', {
        theme: 'snow',
        modules: {
            toolbar: '#toolbar-{{ $tipo }}'
        }
    });
    
    document.getElementById('update-form-{{ $tipo }}').addEventListener('submit', function() {
        document.getElementById('cuerpo-{{ $tipo }}').value = quill_{!! $tipo !!}.root.innerHTML;
    });
</script>