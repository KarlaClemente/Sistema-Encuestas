<?php
namespace App\Http\DTOs\out;

use App\Models\Correo;
use Carbon\Carbon;

final readonly class DtoCorreoOut
{
    public function __construct(
        public int $id,
        public int $idPlantilla,
        public int $idTokenParticipante,
        public Carbon $fechaEnvio,
        public int $numeroRecordatorio,
        public string $estado,
    ) {}

    public static function fromModel(Correo $correo): self
    {
        return new self(
            id: $correo->id_correo,
            idPlantilla: $correo->id_plantilla,
            idTokenParticipante: $correo->id_token_participante,
            fechaEnvio: Carbon::parse($correo->fecha_envio),
            numeroRecordatorio: $correo->numero_recordatorio,
            estado: $correo->estado,
        );
    }
}