<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal', function (Blueprint $table) {
            $table->id();
            $table->string('nombres_apellidos');
            $table->string('cedula_identidad');
            $table->string('cargo');
            $table->foreignId('cod_nucleo');
            $table->boolean('jefe')->default(0);
            $table->string('descripcion_cargo')->nullable();
            $table->string('correo')->nullable();
            $table->string('firma')->nullable();
            $table->foreignId('departamento_id')
                ->constrained('departamentos');
            $table->foreignId('nivel_id')
            ->constrained('nivel')->nullable();
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
        Schema::dropIfExists('personal');
    }
}
