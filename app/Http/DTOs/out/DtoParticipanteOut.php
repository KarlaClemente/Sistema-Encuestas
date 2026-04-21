<?php

namespace App\Http\DTOs\out;

use App\Models\Participante;

final readonly class DtoParticipanteOut
{
    public function __construct(
        public int $idParticipante,
        public string $nombre,
        public string $email,
    ) {}

    public static function fromModel(Participante $participante): self
    {
        return new self(
            idParticipante: $participante->id_participante,
            nombre: $participante->nombre,
            email: $participante->email,
        );
    }
}
