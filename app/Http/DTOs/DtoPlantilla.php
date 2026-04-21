<?php

namespace App\Http\DTOs;

readonly class DtoPlantilla
{
    public function __construct(
        public int $id,
        public string $tipo,
        public string $asunto,
        public string $cuerpo,
    ) {}

    public static function fromArray(array $request, string $tipoPregunta): self
    {
        return new self(
            id: $request['id_plantilla'],
            tipo: $request['tipo'],
            asunto: $request['asunto'],
            texto: $arr['texto'],
        );
    }

    public function toArray(): array
    {
        return [
            'id_plantilla' => $this->$id,
            'tipo' => $this->$tipo,
            'asunto' => $this->$asunto,
            'cuerpo' => $this->$cuerpo,
        ];
    }

    public function toArrayWithoutId(): array
    {
        return array_slice($this->toArray(), 1);
    }
}
