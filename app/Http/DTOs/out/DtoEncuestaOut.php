<?php

namespace App\Http\DTOs\out;

use App\Models\Encuesta;
use Carbon\Carbon;

final readonly class DtoEncuestaOut
{
    public function __construct(
        public int $id,
        public ?int $idTipoEncuesta,
        public int $idGrupo,
        public string $nombreGrupo,
        public ?int $idEncuestaPlantilla = null,
        public ?string $titulo = null,
        public ?string $descripcion = null,
        public ?Carbon $fechaInicio = null,
        public ?Carbon $fechaTermino = null,
        public ?string $textoAdvertencia = null,
        public ?string $estilo = null,
        public array $preguntas = [],
        public bool $completada = false,
    ) {}

    public static function fromModel(Encuesta $encuesta): self
    {
        return new self(
            id: $encuesta->id_encuesta,
            idTipoEncuesta: $encuesta->id_tipo_encuesta,
            idGrupo: $encuesta->id_grupo,
            nombreGrupo: $encuesta->grupo->nombre,
            idEncuestaPlantilla: $encuesta->id_plantilla,
            titulo: $encuesta->titulo,
            descripcion: $encuesta->descripcion,
            fechaInicio: $encuesta->fecha_inicio ? Carbon::parse($encuesta->fecha_inicio) : null,
            fechaTermino: $encuesta->fecha_termino ? Carbon::parse($encuesta->fecha_termino) : null,
            textoAdvertencia: $encuesta->texto_advertencia,
            estilo: $encuesta->estilo,
            preguntas: $encuesta->preguntas?->map(function ($pregunta) {
                return DtoPreguntaOut::fromModel($pregunta);
            })->toArray() ?? [],
            completada: $encuesta->completada,
        );
    }

    public static function fromModelWithoutPreguntas(Encuesta $encuesta): self
    {
        return new self(
            id: $encuesta->id_encuesta,
            idTipoEncuesta: $encuesta->id_tipo_encuesta,
            idGrupo: $encuesta->id_grupo,
            nombreGrupo: $encuesta->grupo->nombre,
            idEncuestaPlantilla: $encuesta->id_plantilla,
            titulo: $encuesta->titulo,
            descripcion: $encuesta->descripcion,
            fechaInicio: $encuesta->fecha_inicio ? Carbon::parse($encuesta->fecha_inicio) : null,
            fechaTermino: $encuesta->fecha_termino ? Carbon::parse($encuesta->fecha_termino) : null,
            textoAdvertencia: $encuesta->texto_advertencia,
            estilo: $encuesta->estilo,
            preguntas: [],
            completada: $encuesta->completada,
        );
    }
}
