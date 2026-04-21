@props([
    'pregunta' => null,
])

<button type="button" class="btn btn-outline-primary"
        data-bs-toggle="modal"
        data-bs-target="#edit-modal"
        data-bs-id="{{ $pregunta->idPregunta }}">Editar</button>