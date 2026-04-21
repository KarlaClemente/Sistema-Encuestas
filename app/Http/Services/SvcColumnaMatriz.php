<?php

namespace App\Http\Services;

use App\Http\DTOs\in\DtoComponentePreguntaIn;
use App\Http\DTOs\out\DtoColumnaMatrizOut;
use App\Models\ColumnaMatriz;

class SvcColumnaMatriz implements SvcComponentePregunta
{
    /**
     * Obtiene las columnas de una pregunta especificas
     *
     * @param  int  $idPregunta  ID de la pregunta de la que se obtienen sus columnas
     * @return DtoColumnaMatrizOut[]
     */
    public function getByPreguntaId(int $idPregunta): array
    {
        $columnas = ColumnaMatriz::where('id_pregunta', $idPregunta)
            ->orderBy('orden')
            ->get()
            ->map(function ($columna) {
                return DtoColumnaMatrizOut::fromModel($columna);
            });

        return $columnas->toArray();
    }

    /**
     * Almacena la información de la columna
     *
     * @param  DtoComponentePreguntaIn  $dto  DTO con la información de la columna
     * @return DtoColumnaMatrizOut DTO con la información de la columna creada
     */
    public function store(DtoComponentePreguntaIn $in): DtoColumnaMatrizOut
    {
        $columna = ColumnaMatriz::create($in->toArray());

        return DtoColumnaMatrizOut::fromModel($columna);
    }

    /**
     * Actualiza la información de una columna
     *
     * @param  DtoComponentePreguntaIn  $in  DTO con la información de la columna a actualizar
     * @return DtoColumnaMatrizOut DTO con la información de la columna actualizada
     */
    public function update(DtoComponentePreguntaIn $in): DtoColumnaMatrizOut
    {
        $columna = ColumnaMatriz::findOrFail($in->id);
        $columna->update($in->toArray());

        return DtoColumnaMatrizOut::fromModel($columna);
    }

    /**
     * Elimina una columna especifica
     *
     * @param  int  $id  ID de la columna a eliminar
     * @return bool true en caso de que se elimine correctamente, false en caso contrario
     */
    public function delete(int $id): bool
    {
        $columna = ColumnaMatriz::findOrFail($id);

        return $columna->delete();
    }
}
