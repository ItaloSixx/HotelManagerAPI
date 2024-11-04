<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\ImportXmlCommand;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('import:xml', function () {
    Artisan::call(ImportXmlCommand::class);
})->describe('Importa o XML');

Artisan::command('schedule:run', function () {
    Artisan::call('schedule:run');
})->describe('Executa o agendamento do XML');
