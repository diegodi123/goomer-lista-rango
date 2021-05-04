<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_categoria');
            $table->foreign('id_categoria')->references('id')->on('categorias');
            $table->unsignedBigInteger('id_restaurante');
            $table->foreign('id_restaurante')->references('id')->on('restaurantes');
            $table->string('nome', 100);
            $table->decimal('preco', 8, 2);
            $table->string('foto', 100);
            $table->boolean('is_promocao')->default(false);
            $table->string('descricao_promocao', 100)->nullable();
            $table->decimal('preco_promocional', 8, 2)->nullable();
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
        Schema::dropIfExists('produtos');
    }
}
