<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompetenciaUserTable extends Migration
{
    public function up()
    {
        Schema::create('competencia_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competencia_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('competencia_user');
    }
}