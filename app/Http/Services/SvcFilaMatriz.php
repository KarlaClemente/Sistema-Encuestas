<?php

namespace App\Http\Services;

use App\Http\DTOs\in\DtoComponentePreguntaIn;
use App\Http\DTOs\out\DtoFilaMatrizOut;
use App\Models\FilaMatriz;

class SvcFilaMatriz implements SvcComponentePregunta
{
    /**
     * Obtiene las filas de una pregunta especificas
     *
     * @return DtoFilaMatrizOut[]
     */
    public function getByPreguntaId(int $idPregunta): array
    {
        $filas = FilaMatriz::where('id_pregunta', $idPregunta)
            ->orderBy('orden')
            ->get()
            ->map(function ($fila) {
                return DtoFilaMatrizOut::fromModel($fila);
            });

        return $filas->toArray();
    }

    /**
     * Almacena la información de la fila
     */
    public function store(DtoComponentePreguntaIn $in): DtoFilaMatrizOut
    {
        $fila = FilaMatriz::create($in->toArray());

        return DtoFilaMatrizOut::fromModel($fila);
    }

    /**
     * Actualiza la información de una fila
     */
    public function update(DtoComponentePreguntaIn $in): DtoFilaMatrizOut
    {
        $fila = FilaMatriz::findOrFail($in->id);
        $fila->update($in->toArray());

        return DtoFilaMatrizOut::fromModel($fila);
    }

    /**
     * Elimina una fila especifica
     */
    public function delete(int $id): bool
    {
        $fila = FilaMatriz::findOrFail($id);

        return $fila->delete();
    }
}
