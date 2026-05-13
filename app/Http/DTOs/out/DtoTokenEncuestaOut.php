<?php

namespace App\Http\DTOs\out;

use App\Models\TokenEncuesta;

final readonly class DtoTokenEncuestaOut
{
    public function __construct(
        public int $idTokenEncuesta,
        public int $idTokenParticipante,
        public int $idEncuesta,
        public bool $completado,
    ) {}

    public static function fromModel(TokenEncuesta $tokenEncuesta): self
    {
        return new self(
            idTokenEncuesta: $tokenEncuesta->id_token_encuesta,
            idTokenParticipante: $tokenEncuesta->id_token_participante,
            idEncuesta: $tokenEncuesta->id_encuesta,
            completado: $tokenEncuesta->completado,
        );
    }
}
