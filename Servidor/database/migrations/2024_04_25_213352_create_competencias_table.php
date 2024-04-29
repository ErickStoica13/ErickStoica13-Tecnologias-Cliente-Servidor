<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompetenciasTable extends Migration
{
    public function up()
    {
        Schema::create('competencias', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            // Outros campos, se necessário
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('competencias');
    }
}