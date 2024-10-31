<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoPrenda;

class TipoPrendaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoPrenda::create([
            'descripcion'     => 'BATA LABORATORIO TALLER',
        ]);
        TipoPrenda::create([
            'descripcion'     => 'IMPERMEABLE',
        ]);
        TipoPrenda::create([
            'descripcion'     => 'GORRO',
        ]);
        TipoPrenda::create([
            'descripcion'     => 'BRAGA',
        ]);
        TipoPrenda::create([
            'descripcion'     => 'NO APLICA',
        ]);
    }
}
