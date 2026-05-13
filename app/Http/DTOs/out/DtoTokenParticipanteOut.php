<?php

namespace App\Http\DTOs\out;

use App\Models\TokenParticipante;

final readonly class DtoTokenParticipanteOut
{
    public function __construct(
        public int $idTokenParticipante,
        public string $token,
        public string $nombreParticipante,
        public string $emailParticipante,
    ) {}

    public static function fromModel(TokenParticipante $tokenParticipante, ?DtoParticipanteOut $participante = null): self
    {
        $participante ??= DtoParticipanteOut::fromModel($tokenParticipante->participante);

        return new self(
            idTokenParticipante: $tokenParticipante->id_token_participante,
            token: $tokenParticipante->token,
            nombreParticipante: $participante->nombre,
            emailParticipante: $participante->email,
        );
    }
}
