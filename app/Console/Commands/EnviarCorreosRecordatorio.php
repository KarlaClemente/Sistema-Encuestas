<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Http\Services\SvcEncuesta;
use Illuminate\Support\Facades\Log;

#[Signature('correos:enviar-recordatorios')]
#[Description('Envia los correos de recordatorio de encuestas en progreso')]
class EnviarCorreosRecordatorio extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(SvcEncuesta $svcEncuesta)
    {
        try {
            $now = Carbon::now();
            $encuestasEnProgreso = $svcEncuesta->getEncuestasEnProgreso();
            foreach ($encuestasEnProgreso as $encuesta) {
                $fechaTermino = $encuesta->fechaTermino;
                $fechaInicio = $encuesta->fechaInicio;
                $minutosEntreRecordatorios = $fechaTermino->diffInMinutes($fechaInicio, True)/4;
                $minutosTranscurridos = $now->diffInMinutes($fechaInicio, true);
                $numeroRecordatorio = (int) ($minutosTranscurridos / $minutosEntreRecordatorios);
                if (1 <= $numeroRecordatorio && $numeroRecordatorio <= 3) {
                    $fechaEnvio = $fechaInicio->copy()->addMinutes($minutosEntreRecordatorios * $numeroRecordatorio);
                    $svcEncuesta->storeCorreos($encuesta->id, 'recordatorio', $fechaEnvio, $numeroRecordatorio);
                }
            }
        } catch (\Throwable $t) {
            Log::error("Error al agendar recordatorios:" . $t->getMessage());
        }
    }
}
