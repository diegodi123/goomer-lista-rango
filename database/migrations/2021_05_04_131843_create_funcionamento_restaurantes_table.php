<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFuncionamentoRestaurantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('funcionamento_restaurantes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_restaurante');
            $table->foreign('id_restaurante')->references('id')->on('restaurantes');
            $table->string('dias', 100);
            $table->string('horario_inicial', 5);
            $table->string('horario_final', 5);
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
        Schema::dropIfExists('funcionamento_restaurantes');
    }
}
