<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentosTemporalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos_temporal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('documento_id')
                    ->constrained('documentos');
            $table->string('departamentos_destino');
            $table->string('departamentos_copias')->nullable();
            $table->boolean('tieneCopia')->default(false);
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
        Schema::dropIfExists('documentos_temporal');
    }
}
