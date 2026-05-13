<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Http\Services\SvcCorreo;

#[Signature('correos:enviar-pendientes')]
#[Description('Envía los correos pendientes de encuestas programados')]
class EnviarCorreosPendientes extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(SvcCorreo $svc)
    {
        try {
            $svc->enviarCorreoPendientes();
        } catch (\Exception $e) {
            Log::error("Error al enviar correos pendientes:" . $e->getMessage());
        }
    }
}
