<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentosExternosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos_externos', function (Blueprint $table) {
            $table->id();
            $table->string('numero_oficio')->unique();
            $table->foreignId('id_remitente')
            ->constrained('remitentes_externos');
            $table->string('contenido');
            $table->string('estatus')->nullable();
            $table->date('fecha_oficio')->nullable()->default(null);
            $table->foreignId('departamento_receptor')
                ->constrained('departamentos');
            $table->foreignId('documento_respuesta')
                ->constrained('documentos');
            $table->date('fecha_entrada')->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documentos_externos');
    }
}
