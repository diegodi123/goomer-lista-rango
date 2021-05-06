<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromocaoProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promocao_produtos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_produto');
            $table->foreign('id_produto')->references('id')->on('produtos');
            $table->date('dia');
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
        Schema::dropIfExists('promocao_produtos');
    }
}
