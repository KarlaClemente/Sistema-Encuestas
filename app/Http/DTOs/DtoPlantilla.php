<?php
namespace App\Http\DTOs;

use App\Models\Plantilla;
use Illuminate\Http\Request;

final readonly class DtoPlantilla
{
    public function __construct(
        public int $idPlantilla,
        public string $tipo,
        public string $asunto,
        public string $cuerpo,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return self::fromArray($request->validated());
    }

    public static function fromArray(array $arr): self
    {
        return new self(
            idPlantilla: (int) $arr['id_plantilla'],
            tipo: $arr['tipo'],
            asunto: $arr['asunto'],
            cuerpo: $arr['cuerpo'],
        );
    }

    public static function fromModel(Plantilla $plantilla): self
    {
        return new self(
            idPlantilla : $plantilla->id_plantilla,
            tipo : $plantilla->tipo,
            asunto : $plantilla->asunto,
            cuerpo : $plantilla->cuerpo,
        );
    }

    public function toArray(): array
    {
        return [
            'asunto' => $this->asunto,
            'cuerpo' => $this->cuerpo,
        ];
    }

    public function toUpdateArray(): array {
        return ['asunto' => $this->asunto, 'cuerpo' => $this->cuerpo];
    }
}