<?php
namespace App\Http\DTOs\out;

use App\Models\FilaMatriz;

final readonly class DtoFilaMatrizOut
{
    public function __construct(
        public int $id,
        public int $idPregunta,
        public string $texto,
        public int $orden,
    ) {}

    public static function fromModel(FilaMatriz $filaMatriz): self
    {
        return new self(
            id: (int) $filaMatriz->id_fila_matriz,
            idPregunta: (int) $filaMatriz->id_pregunta,
            texto: $filaMatriz->texto,
            orden: (int) $filaMatriz->orden,
        );
    }
}