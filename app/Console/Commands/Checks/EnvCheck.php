<?php

namespace App\Console\Commands\Checks;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class EnvCheck extends Command
{
    protected $signature = 'env:check';
    protected $description = 'Check environment variables';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $variablesToCheck = Config::get('env-check.environment');

        foreach ($variablesToCheck as $variable) {
            if (env($variable) === null) {
                $this->error("La variable de entorno '$variable' no está configurada.");
            }
        }
        $this->info('Todas las variables de entorno necesarias están configuradas correctamente.');
    }
}
