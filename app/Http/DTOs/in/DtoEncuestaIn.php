<?php

namespace App\Http\DTOs\in;

use Carbon\Carbon;
use Illuminate\Http\Request;

final readonly class DtoEncuestaIn
{
    public function __construct(
        public ?int $id,
        public ?int $idTipoEncuesta,
        public int $idGrupo,
        public ?int $idEncuestaPlantilla,
        public ?string $titulo,
        public ?string $descripcion,
        public ?Carbon $fechaInicio,
        public ?Carbon $fechaTermino,
        public ?string $textoAdvertencia,
        public ?string $estilo,
        public bool $completada = false
    ) {}

    public static function fromRequest(Request $request): self
    {
        return self::fromArray($request->validated());
    }

    public static function fromArray(array $arr): self
    {
        return new self(
            id: isset($arr['id_encuesta']) ? (int) $arr['id_encuesta'] : null,
            idTipoEncuesta: isset($arr['id_tipo_encuesta']) ? (int) $arr['id_tipo_encuesta'] : null,
            idGrupo: (int) $arr['id_grupo'],
            idEncuestaPlantilla: isset($arr['id_plantilla']) ? (int) $arr['id_plantilla'] : null,
            titulo: $arr['titulo'] ?? null,
            descripcion: $arr['descripcion'] ?? null,
            fechaInicio: isset($arr['fecha_inicio']) ? Carbon::parse($arr['fecha_inicio']) : null,
            fechaTermino: isset($arr['fecha_termino']) ? Carbon::parse($arr['fecha_termino']) : null,
            textoAdvertencia: $arr['texto_advertencia'] ?? null,
            estilo: $arr['estilo'] ?? null,
            completada: $arr['completada'] ?? false,
        );
    }

    public function toArray(): array
    {
        return [
            'id_encuesta' => $this->id,
            'id_tipo_encuesta' => $this->idTipoEncuesta,
            'id_grupo' => $this->idGrupo,
            'id_plantilla' => $this->idEncuestaPlantilla,
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'fecha_inicio' => $this->fechaInicio?->format('Y-m-d H:i:s'),
            'fecha_termino' => $this->fechaTermino?->format('Y-m-d H:i:s'),
            'texto_advertencia' => $this->textoAdvertencia,
            'estilo' => $this->estilo,
            'completada' => $this->completada,
        ];
    }
}
