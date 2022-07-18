<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Artisan;
use Illuminate\Support\Facades\Artisan as FacadesArtisan;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cultores:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Instalación directa del API Cultores';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Generando key de la APP!");
        Artisan::call('key:generate');

        $this->info("Construyendo la base de datos!");
        //publicando migraciones de Spatie
        Artisan::call('vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"');
        $this->info("Publicacion de Spatie Ejecutada!");
        //ejecutar las migraciones
        Artisan::call('migrate');
        $this->info("Migraciones Ejecutadas!");

        //ejecutar los seed
        Artisan::call('db:seed');
        $this->info("Seeder Ejecutados!");
        
        //creando el enlace publico
        $this->info("Creando el enlace publico!");
        Artisan::call('storage:link');

        $this->info("Instalación realizada con éxito!"); 
    }
}
