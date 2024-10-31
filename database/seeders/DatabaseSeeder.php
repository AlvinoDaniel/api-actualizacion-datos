<?php

namespace Database\Seeders;

use App\Models\TipoPersonal;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // UserSeeder::class,
            NucleosSeeder::class,
            AreaTrabajoSeeder::class,
            TipoCalzadoSeeder::class,
            TipoPersonalSeeder::class,
            TipoPrendaSeeder::class,
        ]);
    }
}
