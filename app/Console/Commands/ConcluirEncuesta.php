<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Http\Services\SvcEncuesta;

#[Signature('encuesta:concluir-encuestas')]
#[Description('Concluye las encuestas que han llegado a su fecha de término')]
class ConcluirEncuesta extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(SvcEncuesta $svcEncuesta)
    {
        try {
            $svcEncuesta->concluirEncuestas();
        } catch (\Exception $e) {
            Log::error('Error al concluir encuestas:' . $e->getMessage());
        }
    }
}
