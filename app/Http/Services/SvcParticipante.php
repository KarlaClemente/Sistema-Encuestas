<?php

namespace App\Http\Services;

use App\Http\DTOs\out\DtoParticipanteOut;
use App\Models\Participante;

class SvcParticipante
{
    public function show(int $id): DtoParticipanteOut
    {
        $participante = Participante::findOrFail($id);

        return DtoParticipanteOut::fromModel($participante);
    }
}
