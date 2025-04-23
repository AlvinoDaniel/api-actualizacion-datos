<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Models\User;

class SetPass extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:pass {cedula}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Establecer contraseña generica';

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
        try {
            $this->info("Buscando Personal...");
            $cedula = $this->argument('cedula');
            $user = User::where('cedula', $cedula)->first();
            if(!$user){
                $this->error("Personal no registrado");
                return 0;
            }
            $user->password = Str::substr($cedula, -4);
            $user->save();
            $this->info("Contraseña establecida exitosamente!");
        } catch (\Throwable $th) {
            $this->error("Error al intetar establecer contraseña");
        }
    }
}
