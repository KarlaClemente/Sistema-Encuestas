<?php

namespace App\Http\DTOs\out;

use App\Models\Pregunta;

final readonly class DtoPreguntaOut
{
    public function __construct(
        public int $idPregunta,
        public string $texto,
        public int $orden,
        public int $idTipoPregunta,
        public ?int $idEncuestaPlantilla,
        public ?int $idEncuesta,
        public array $opciones,
        public array $filasMatriz,
        public array $columnasMatriz,
        public int $minSeleccion,
        public int $maxSeleccion,
    ) {}

    public static function fromModel(Pregunta $pregunta): self
    {
        return new self(
            idPregunta: $pregunta->id_pregunta,
            texto: $pregunta->texto,
            orden: $pregunta->orden,
            idTipoPregunta: $pregunta->id_tipo_pregunta,
            idEncuestaPlantilla: $pregunta->id_encuesta_plantilla,
            idEncuesta: $pregunta->id_encuesta,
            opciones: $pregunta->opcionesPregunta?->map(function ($opcion) {
                return DtoOpcionPreguntaOut::fromModel($opcion);
            })
                ->toArray() ?? [],
            filasMatriz: $pregunta->filasMatriz?->map(function ($fila) {
                return DtoFilaMatrizOut::fromModel($fila);
            })
                ->toArray() ?? [],
            columnasMatriz: $pregunta->columnasMatriz?->map(function ($columna) {
                return DtoColumnaMatrizOut::fromModel($columna);
            })
                ->toArray() ?? [],
            minSeleccion : $pregunta->min_seleccion,
            maxSeleccion: $pregunta->max_seleccion,
        );
    }
}
