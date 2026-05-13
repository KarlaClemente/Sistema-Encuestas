<?php
namespace App\Http\DTOs\in;

use Illuminate\Http\Request;
use App\Http\DTOs\DtoPlantilla;
use Carbon\Carbon;

final readonly class DtoCorreoIn
{
    public function __construct(
        public ?int $id,
        public DtoPlantilla $plantilla,
        public int $idTokenParticipante,
        public Carbon $fechaEnvio,
        public int $numeroRecordatorio,
        public string $estado,
    ) {}
    
    public static function crearParaEnvio(DtoPlantilla $plantilla, int $idTokenParticipante, Carbon $fechaEnvio, int $numeroRecordatorio): self
    {
        return new self(
            id: null,
            plantilla: $plantilla,
            idTokenParticipante: $idTokenParticipante,
            fechaEnvio: $fechaEnvio,
            numeroRecordatorio: $numeroRecordatorio,
            estado: 'pendiente',
        );
    }

    public function toArray(): array
    {
        return [
            'id_plantilla' => $this->plantilla->idPlantilla,
            'id_token_participante' => $this->idTokenParticipante,
            'fecha_envio' => $this->fechaEnvio->format('Y-m-d H:i:s'),
            'numero_recordatorio' => $this->numeroRecordatorio,
            'estado' => $this->estado,
        ];
    }

    public function toUpdateArray(): array
    {
        return [
            'fecha_envio' => $this->fechaEnvio->format('Y-m-d H:i:s'),
            'numero_recordatorio' => $this->numeroRecordatorio,
        ];
    }
}