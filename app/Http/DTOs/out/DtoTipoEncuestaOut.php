<?php

namespace App\Http\DTOs\out;

use App\Models\TipoEncuesta;

final readonly class DtoTipoEncuestaOut
{
    public function __construct(
        public int $idTipoEncuesta,
        public string $nombre,
    ) {}

    public static function fromModel(TipoEncuesta $tipoEncuesta): self
    {
        return new self(
            idTipoEncuesta: $tipoEncuesta->id_tipo_encuesta,
            nombre: $tipoEncuesta->nombre,
        );
    }
}
