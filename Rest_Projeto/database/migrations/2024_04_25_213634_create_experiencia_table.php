<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExperienciaTable extends Migration
{
    public function up()
    {
        Schema::create('experiencia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nome_empresa');
            $table->string('inicio');
            $table->string('fim')->nullable();
            $table->string('cargo');
            // Outros campos, se necessário
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('experiencia');
    }
}