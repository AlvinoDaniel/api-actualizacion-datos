<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOficiosDepartamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oficios_departamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oficio_id')
                ->constrained('oficios');
            $table->foreignId('departamento_id')
                ->constrained('departamentos');
            $table->boolean('leido')->default(false);
            $table->boolean('copia')->default(false);
            $table->date('fecha_leido');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oficios_departamentos');
    }
}
