<?php

namespace App\Http\Services;

use App\Http\DTOs\out\DtoTokenParticipanteOut;
use App\Models\TokenParticipante;

class SvcTokenParticipante
{
    public function store(int $idParticipante): DtoTokenParticipanteOut
    {
        $tokenParticipante = new TokenParticipante;
        $tokenParticipante->id_participante = $idParticipante;
        $tokenParticipante->save();

        return DtoTokenParticipanteOut::fromModel($tokenParticipante);
    }

    public function delete(int $id): bool
    {
        $tokenParticipante = TokenParticipante::findOrFail($id);

        return $tokenParticipante->delete();
    }
}
