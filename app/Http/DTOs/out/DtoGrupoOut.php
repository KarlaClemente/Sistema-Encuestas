<?php

namespace App\Http\DTOs\out;

use App\Models\Grupo;

final readonly class DtoGrupoOut
{
    public function __construct(
        public int $idGrupo,
        public string $nombre,
        public ?string $docente,
    ) {}

    public static function fromModel(Grupo $grupo): self
    {
        return new self(
            idGrupo: (int) $grupo->id_grupo,
            nombre: $grupo->nombre,
            docente: $grupo->docente,
        );
    }
}
