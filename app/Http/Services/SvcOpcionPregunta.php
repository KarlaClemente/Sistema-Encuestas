<?php
namespace App\Http\Services;

use App\Models\OpcionPregunta;
use App\Http\Services\SvcComponentePregunta;
use App\Http\DTOs\in\DtoComponentePreguntaIn;
use App\Http\DTOs\out\DtoOpcionPreguntaOut;

class SvcOpcionPregunta implements SvcComponentePregunta
{
    /**
     * Obtiene las opciones de una pregunta especificas
     * @return DtoOpcionPreguntaOut[]
     */
    public function getByPreguntaId(int $idPregunta): array
    {
        $opciones = OpcionPregunta::where('id_pregunta', $idPregunta)
                    ->orderBy('orden')
                    ->get()
                    ->map(function($opcion){
                        return DtoOpcionPreguntaOut::fromModel($opcion);
                    });
        return $opciones->toArray();
    }

    /**
     * Almacena la información de la opción
     */
    public function store(DtoComponentePreguntaIn $in): DtoOpcionPreguntaOut
    {
        $opcion = OpcionPregunta::create($in->toArray());
        return DtoOpcionPreguntaOut::fromModel($opcion);
    }

    /**
     * Actualiza la información de una opcion
     */
    public function update(DtoComponentePreguntaIn $in): DtoOpcionPreguntaOut
    {
        $opcion = OpcionPregunta::findOrFail($in->id);
        $opcion->update($in->toArray());
        return DtoOpcionPreguntaOut::fromModel($opcion);
    }

    /**
     * Elimina una opcion especifica
     */
    public function delete(int $id): bool
    {
        $opcion = OpcionPregunta::findOrFail($id);
        return $opcion->delete();
    }
}