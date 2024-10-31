<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AreaTrabajo;

class AreaTrabajoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AreaTrabajo::create([
            'descripcion'     => 'OFICINA',
            'tipo_personal'   => 2
        ]);
        AreaTrabajo::create([
            'descripcion'     => 'BIBLIOTECA',
            'tipo_personal'   => 2
        ]);
        AreaTrabajo::create([
            'descripcion'     => 'SALUD',
            'tipo_personal'   => 2
        ]);
        AreaTrabajo::create([
            'descripcion'     => 'DEPORTE',
            'tipo_personal'   => 2
        ]);
        AreaTrabajo::create([
            'descripcion'     => 'LABORATORIO',
            'tipo_personal'   => 2
        ]);
        AreaTrabajo::create([
            'descripcion'     => 'TALLERES Y REPRODUCCION',
            'tipo_personal'   => 2
        ]);

        AreaTrabajo::create([
            'descripcion'     => 'BIBLIOTECA Y MANT.',
            'tipo_personal'   => 3
        ]);
        AreaTrabajo::create([
            'descripcion'     => 'MENSAJERIA',
            'tipo_personal'   => 3
        ]);
        AreaTrabajo::create([
            'descripcion'     => 'VIGILANCIA',
            'tipo_personal'   => 3
        ]);
        AreaTrabajo::create([
            'descripcion'     => 'TRANSPORTE',
            'tipo_personal'   => 3
        ]);
        AreaTrabajo::create([
            'descripcion'     => 'COMEDOR',
            'tipo_personal'   => 3
        ]);
        AreaTrabajo::create([
            'descripcion'     => 'LABORATORIO',
            'tipo_personal'   => 3
        ]);
        AreaTrabajo::create([
            'descripcion'     => 'REPRODUCCION',
            'tipo_personal'   => 3
        ]);
        AreaTrabajo::create([
            'descripcion'     => 'AUXI. ANATOMIA',
            'tipo_personal'   => 3
        ]);
        AreaTrabajo::create([
            'descripcion'     => 'UNIDAD DOBLE PROPOSITO',
            'tipo_personal'   => 3
        ]);
        AreaTrabajo::create([
            'descripcion'     => 'TALLERES',
            'tipo_personal'   => 3
        ]);
        AreaTrabajo::create([
            'descripcion'     => 'CHOFERES AUTORIDADES',
            'tipo_personal'   => 3
        ]);
        AreaTrabajo::create([
            'descripcion'     => 'BOMBERO',
            'tipo_personal'   => 3
        ]);
        AreaTrabajo::create([
            'descripcion'     => 'BOMBERO',
            'tipo_personal'   => 2
        ]);


    }
}
