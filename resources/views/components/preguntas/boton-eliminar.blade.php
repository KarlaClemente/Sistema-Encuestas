@props([
    'pregunta' => null,
])

<form action="{{ route('eliminar-pregunta', ['id' => $pregunta->idPregunta]) }}" method="post">
    @csrf
    @method('DELETE')
    <button type="sufbmit" class="btn btn-outline-danger">Eliminar</button>
</form>