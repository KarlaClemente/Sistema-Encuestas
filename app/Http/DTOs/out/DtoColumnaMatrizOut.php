<?php
namespace App\Http\DTOs\out;

use App\Models\ColumnaMatriz;

final readonly class DtoColumnaMatrizOut
{
    public function __construct(
        public int $id,
        public int $idPregunta,
        public string $texto,
        public int $orden,
    ) {}

    public static function fromModel(ColumnaMatriz $columnaMatriz): self
    {
        return new self(
            id: (int) $columnaMatriz->id_columna_matriz,
            idPregunta: (int) $columnaMatriz->id_pregunta,
            texto: $columnaMatriz->texto,
            orden: (int) $columnaMatriz->orden,
        );
    }
}