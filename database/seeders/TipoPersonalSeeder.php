<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoPersonal;

class TipoPersonalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $docente = TipoPersonal::create([
            'descripcion'     => 'DOCENTE',
        ]);

        $administrativo = TipoPersonal::create([
            'descripcion'     => 'ADMINISTRATIVO',
        ]);
        
        $obrero = TipoPersonal::create([
            'descripcion'     => 'OBRERO',
        ]);
    }
}
