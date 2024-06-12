<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVagasTable extends Migration
{
    public function up()
    {
        Schema::create('vagas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ramo_id')->constrained('ramos')->onDelete('cascade');
            $table->string('titulo');
            $table->text('descricao');
            $table->json('competencias');
            $table->integer('experiencia');
            $table->decimal('salario_min', 10, 2);
            $table->decimal('salario_max', 10, 2)->nullable();
            $table->boolean('ativo');
            $table->foreignId('empresa_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vagas');
    }
}
