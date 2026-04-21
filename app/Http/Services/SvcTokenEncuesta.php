<?php

namespace App\Http\Services;

use App\Models\TokenEncuesta;

class SvcTokenEncuesta
{
    public function store(int $idEncuesta, int $idTokenParticipante)
    {
        $tokenEncuesta = new TokenEncuesta;
        $tokenEncuesta->id_token_participante = $idTokenParticipante;
        $tokenEncuesta->id_encuesta = $idEncuesta;
        $tokenEncuesta->completado = false;
        $tokenEncuesta->save();
    }

    public function delete(int $id): bool
    {
        $tokenEncuesta = TokenEncuesta::findOrFail($id);

        return $tokenEncuesta->delete();
    }
}
