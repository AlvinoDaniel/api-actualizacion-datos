<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarpetasDocumentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carpertas_documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('documento_id')
            ->constrained('documentos');
            $table->foreignId('carpeta_id')
            ->constrained('carpetas');
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
        Schema::dropIfExists('carpertas_documentos');
    }
}
