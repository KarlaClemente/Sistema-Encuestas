<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('correos:enviar-pendientes')->everyMinute();
Schedule::command('correos:enviar-recordatorios')->everyMinute();
Schedule::command('encuesta:concluir-encuestas')->everyMinute();