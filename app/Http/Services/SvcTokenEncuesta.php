<?php

namespace App\Http\Services;

use App\Models\TokenEncuesta;
use App\Http\DTOs\out\DtoTokenEncuestaOut;

class SvcTokenEncuesta
{
    /**
     * Almacena un nuevo tokenEncuesta asociado a un tokenParticipante y una encuesta.
     * @param int $idEncuesta ID de la encuesta a la que se asocia.
     * @param int $idTokenParticipante ID del token del participante
     */
    public function store(int $idEncuesta, int $idTokenParticipante): void
    {
        $tokenEncuesta = new TokenEncuesta;
        $tokenEncuesta->id_token_participante = $idTokenParticipante;
        $tokenEncuesta->id_encuesta = $idEncuesta;
        $tokenEncuesta->completado = false;
        $tokenEncuesta->save();
    }

    /**
     * Elimina un tokenEncuesta por su ID
     * @param int $id ID del tokenEncuesta a eliminar
     * @return bool True en caso de que se elimine correctamente, False en caso contrario
     */
    public function delete(int $id): bool
    {
        $tokenEncuesta = TokenEncuesta::findOrFail($id);

        return $tokenEncuesta->delete();
    }

    /**
     * Obtiene los tokensEncuesta a de una encuesta filtrados por su estado de completado
     * @param int $idEncuesta ID de la encuesta de la que se quiere obtener los tokensEncuesta
     * @param bool $esCompletado True en caso de que se quieran los tokensEncuesta completados, False en caso contrario
     * @return DtoTokenEncuestaOut[] Arreglo con DTO's de los tokenEncuesta obtenidos
     */
    public function getByIdEncuestaByCompletado(int $idEncuesta, bool $esCompletado): array
    {
        return TokenEncuesta::where('id_encuesta', $idEncuesta)
                ->where('completado', $esCompletado)
                ->get()
                ->map(fn($tokenEncuesta) => DtoTokenEncuestaOut::fromModel($tokenEncuesta))
                ->toArray();
    }
}
