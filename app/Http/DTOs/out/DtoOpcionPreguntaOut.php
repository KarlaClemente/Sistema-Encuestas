<?php

namespace App\Http\DTOs\out;

use App\Models\OpcionPregunta;

final readonly class DtoOpcionPreguntaOut
{
    public function __construct(
        public int $id,
        public int $idPregunta,
        public string $texto,
        public int $orden,
    ) {}

    public static function fromModel(OpcionPregunta $opcion): self
    {
        return new self(
            id: (int) $opcion->id_opcion,
            idPregunta: (int) $opcion->id_pregunta,
            texto: $opcion->texto,
            orden: (int) $opcion->orden,
        );
    }
}
