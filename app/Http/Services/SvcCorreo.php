<?php

namespace App\Http\Services;

use Carbon\Carbon;
use App\Mail\CorreoEncuesta;
use App\Http\DTOs\in\DtoCorreoIn;
use App\Http\DTOs\DtoPlantilla;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Correo;

class SvcCorreo
{
    public function store(DtoCorreoIn $dto)
    {
        $existe = Correo::where('id_token_participante', $dto->idTokenParticipante)
                    ->where('numero_recordatorio', $dto->numeroRecordatorio)
                    ->where('id_plantilla', $dto->plantilla->idPlantilla)
                    ->exists();
        if ($existe) {
            return;
        }
        Correo::create($dto->toArray());
    }

    public function update(DtoCorreoIn $dto)
    {
        $correo = Correo::findOrFail($dto->id);
        $correo->update($dto->toUpdateArray());
    }

    public function delete(int $id): bool
    {
        $correo = Correo::findOrFail($id);
        return $correo->delete();
    }

    public function enviarCorreoPendientes(): void
    {
        $correos = Correo::where('estado', 'pendiente')
                    ->where('fecha_envio', '<=', now())
                    ->with(['plantilla', 'tokenParticipante.participante', 'tokenParticipante.tokensEncuesta.encuesta'])
                    ->get();
        foreach ($correos as $correo) {
            try {
                $plantilla = DtoPlantilla::fromModel($correo->plantilla);
                $tokenParticipante = $correo->tokenParticipante;
                $tokensEncuesta = $tokenParticipante->tokensEncuesta;
                
                $encuesta = $tokensEncuesta->count() === 1? $tokensEncuesta->first()->encuesta : $tokensEncuesta->first()->encuesta->encuestaPlantilla;

                $fechaInicio = Carbon::parse($encuesta->fecha_inicio);
                $dto = DtoCorreoIn::crearParaEnvio($plantilla, $tokenParticipante->id_token_participante, $fechaInicio, $correo->numero_recordatorio);
                $nombreParticipante = $tokenParticipante->participante->nombre;
                $fechaTermino = Carbon::parse($encuesta->fecha_termino);
                $titulo = $encuesta->titulo;
                $enlaceEncuesta = route('contestar-encuesta', ['token' => $tokenParticipante]);

                Mail::to($tokenParticipante->participante->email)
                ->queue(new CorreoEncuesta(
                    $dto,
                    $nombreParticipante,
                    $titulo,
                    $fechaInicio,
                    $fechaTermino,
                    $enlaceEncuesta
                ));
                $correo->update(['estado' => 'enviado']);
            } catch (\Exception $e) {
                $correo->update(['estado' => 'fallido']);
                Log::error("Falló al enviar correo" . $e->getMessage(), [
                    'participante_id' => $tokenParticipante->participante->id_participante,
                    'tipo_correo' => $plantilla->tipo,
                    'encuesta_id' => $encuesta->id,
                ]);
            }
        }
    }
}