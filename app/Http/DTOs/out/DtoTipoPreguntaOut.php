<?php

namespace App\Http\DTOs\out;

use App\Models\TipoPregunta;

final readonly class DtoTipoPreguntaOut
{
    public function __construct(
        public int $idTipoPregunta,
        public string $nombre,
    ) {}

    public static function fromModel(TipoPregunta $tipoPregunta): self
    {
        return new self(
            idTipoPregunta: $tipoPregunta->id_tipo_pregunta,
            nombre: $tipoPregunta->nombre,
        );
    }
}
