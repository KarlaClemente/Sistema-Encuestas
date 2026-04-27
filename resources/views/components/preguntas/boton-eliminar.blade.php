@props([
    'pregunta' => null,
])

<form action="{{ route('eliminar-pregunta', ['id' => $pregunta->idPregunta]) }}" method="post">
    @csrf
    @method('DELETE')
    <button type="sufbmit" class="btn btn-outline-danger" onclick="return confirm('¿Eliminar esta pregunta?')">
        <i class="bi bi-trash"></i>
    </button>
</form>