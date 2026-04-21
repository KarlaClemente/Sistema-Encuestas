<?php

namespace App\Http\DTOs;

use Illuminate\Http\Request;

final readonly class DtoEncuestaPlantilla
{
    public function __construct(
        public ?int $id,
        public int $idTipoEncuesta,
        public string $titulo,
        public ?string $descripcion,
        public string $estilo
    ) {}

    public static function fromRequest(Request $request): self
    {
        return self::fromArray($request->validated());
    }

    public static function fromArray(array $arr): self
    {
        return new self(
            id: $arr['id_encuesta_plantilla'] ?? null,
            idTipoEncuesta: (int) $arr['id_tipo_encuesta'],
            titulo: $arr['titulo'],
            descripcion: $arr['descripcion'] ?? null,
            estilo: $arr['estilo'],
        );
    }

    public static function fromModel(EncuestaPlantilla $encuestaPlantilla): self
    {
        return new self(
            id: $encuestaPlantilla->id_encuesta_plantilla,
            idTipoEncuesta: $encuestaPlantilla->id_tipo_encuesta,
            titulo: $encuestaPlantilla->titulo,
            descripcion: $encuestaPlantilla->descripcion,
            estilo: $encuestaPlantilla->estilo,
        );
    }

    public function toArray(): array
    {
        return [
            'id_encuesta_plantilla' => $this->id,
            'id_tipo_encuesta' => $this->idTipoEncuesta,
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'estilo' => $this->estilo,
        ];
    }
}
