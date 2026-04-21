<?php

namespace App\Http\Services;

use App\Http\DTOs\out\DtoGrupoOut;
use App\Http\DTOs\out\DtoParticipanteOut;
use App\Models\Grupo;

class SvcGrupo
{
    public function index(): array
    {
        $dtos = Grupo::all()
            ->map(function ($grupo) {
                return DtoGrupoOut::fromModel($grupo);
            });

        return $dtos->toArray();
    }

    public function participantes(int $idGrupo): array
    {
        $grupo = Grupo::with('gruposParticipante.participante')->findOrFail($idGrupo);

        return $grupo->gruposParticipante
            ->map(fn ($grupoParticipante) => DtoParticipanteOut::fromModel($grupoParticipante->participante))
            ->toArray();
    }

    public function show(int $id)
    {
        $grupo = Grupo::findOrFail($id);

        return DtoGrupoOut::fromModel($grupo);
    }
}
