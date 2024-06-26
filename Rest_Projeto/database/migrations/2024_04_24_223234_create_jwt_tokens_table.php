<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJwtTokensTable extends Migration
{
    public function up()
    {
        Schema::create('jwt_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token', 350)->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jwt_tokens');
    }
}