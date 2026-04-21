<?php

namespace App\Http\Services;

use App\Http\DTOs\out\DtoTipoPreguntaOut;
use App\Models\TipoPregunta;

class SvcTipoPregunta
{
    /**
     * Obtiene todos los tipos de preguntas
     *
     * @return DtoPreguntasOut[]
     */
    public function index(): array
    {
        $dtos = TipoPregunta::all()
            ->map(function ($tipo) {
                return DtoTipoPreguntaOut::fromModel($tipo);
            });

        return $dtos->toArray();
    }

    /**
     * Obtiene un tipo de pregunta especifico
     */
    public function getById(int $id): DtoTipoPreguntaOut
    {
        $tipoPregunta = TipoPregunta::findOrFail($id);

        return DtoTipoPreguntaOut::fromModel($tipoPregunta);
    }

    /**
     * Obtiene el nombre tipo de pregunta especifico
     */
    public function getNombreById(int $id): string
    {
        return $this->getById($id)->nombre;
    }
}
