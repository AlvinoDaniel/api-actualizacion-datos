<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->mediumText('asunto');
            $table->bigInteger('nro_documento')->nullable();
            $table->longText('contenido');
            $table->string('tipo_documento');
            $table->string('estatus');
            $table->date('fecha_enviado')->nullable();
            $table->foreignId('departamento_id')
                ->constrained('departamentos');
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
        Schema::dropIfExists('documentos');
    }
}
