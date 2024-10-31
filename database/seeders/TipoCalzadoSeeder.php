<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoCalzado;

class TipoCalzadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoCalzado::create([
            'descripcion'     => 'BOTA',
        ]);
        TipoCalzado::create([
            'descripcion'     => 'ZAPATO',
        ]);
        TipoCalzado::create([
            'descripcion'     => 'AMBOS',
        ]);
    }
}
