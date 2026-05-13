<?php

namespace App\Http\Services;

use App\Http\DTOs\out\DtoTokenParticipanteOut;
use App\Http\DTOs\out\DtoParticipanteOut;
use App\Models\TokenParticipante;

class SvcTokenParticipante
{
    public function store(DtoParticipanteOut $participante): DtoTokenParticipanteOut
    {
        $tokenParticipante = new TokenParticipante;
        $tokenParticipante->id_participante = $participante->idParticipante;
        $tokenParticipante->save();

        return DtoTokenParticipanteOut::fromModel($tokenParticipante, $participante);
    }

    public function delete(int $id): bool
    {
        $tokenParticipante = TokenParticipante::findOrFail($id);

        return $tokenParticipante->delete();
    }
}
