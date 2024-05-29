<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('competencia_id')->nullable()->constrained('competencias')->onDelete('set null');
            $table->foreignId('experiencia_id')->nullable()->constrained('experiencia')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['competencia_id']);
            $table->dropForeign(['experiencia_id']);
            $table->dropColumn(['competencia_id', 'experiencia_id']);
        });
    }
}
