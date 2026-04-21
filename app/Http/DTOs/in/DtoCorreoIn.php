<?php

namespace App\Http\DTOs;

use App\DTOs\DtoPlantilla;
use Illuminate\Http\Request;

readonly class DtoCorreo extends DtoPlantilla
{
    public function __construct(
        ?int $id,
        public ?int $idTokenParticipante,
        public int $idPlantilla,
        string $tipo,
        string $asunto,
        string $cuerpo,
        public DateTime $fechaEnvio,
        public int $nuemeroRecordatorio
    ) {
        parent::__construct($id, $tipo, $asunto, $cuerpo);
    }

    public static function fromRequest(Request $request): self
    {
        return self::fromArray($request->all());
    }

    public static function fromArray(array $arr): self
    {
        return new self(
            idTokenParticipante: $arr['id_token_participante'] ?? null,
            idPlantilla: $arr['id_plantilla'],
            tipo: $arr['tipo'],
            aasunto: $arr['asunto'],
            cuerpo: $arr['cuerpo'],
            fechaEnvio: $arr['fecha_envio'],
            numeroRecordatorio: $arr['numero_recordatorio'],
        );
    }

    public static function toArray(): array
    {
        return array_merge([
            'id_correo' => $this->$id,
            'id_token_participante' => $this->$idTokenParticipante,
            'id_plantilla' => $this->$idPlantilla,
            'fechaEnvio' => $this->fechaEnvio,
            'numero_recordatorio' => $this->$nuemeroRecordatorio,
        ],
            parent::toArrayWithoutId());
    }
}
