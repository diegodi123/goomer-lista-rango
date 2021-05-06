<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Produtos;

class ProdutosTest extends TestCase
{
    public function testAtributosProdutos()
    {
        $this->assertClassHasAttribute('nome', Produtos::class);
        $this->assertClassHasAttribute('preco', Produtos::class);
        $this->assertClassHasAttribute('id_categoria', Produtos::class);
        $this->assertClassHasAttribute('id_restaurante', Produtos::class);
        $this->assertClassHasAttribute('is_promocao', Produtos::class);
        $this->assertClassHasAttribute('descricao', Produtos::class);
        $this->assertClassHasAttribute('preco_promocional', Produtos::class);
        $this->assertClassHasAttribute('promocao', Produtos::class);
    }

    public function testProdutoEmpty()
    {
        $promocao = array(
            'dias' => 'Segunda a quinta',
            'horario_inicial' => '11:00',
            'horario_final' => '22:00');

        $produto = new Produtos('Whopper', 20, 2, 1, true, "Whopper pela metade do preço", 10, $promocao);

        $this->assertTrue($produto->isArrayPromocao(), "Na promoção, dias da semana e horários devem estar preenchidos.");
        $this->assertNotEmpty($produto->getNome());
        $this->assertNotEmpty($produto->getPreco());
        $this->assertNotEmpty($produto->getIdCategoria());
        $this->assertNotEmpty($produto->getIdRestaurante());
        $this->assertEquals(true,$produto->getIsPromocao());
    }

    public function testPromocaoProduto()
    {
        $promocao = array(
            'dias' => 'Segunda a segunda',
            'horario_inicial' => '10:30',
            'horario_final' => '16:00');

        $produto = new Produtos('Whopper', 20, 2, 1, true, "Whopper pela metade do preço", 10, $promocao);

        $this->assertTrue($produto->isHorarioInicialValido(), "Horário inicial inválido.");
        $this->assertTrue($produto->isHorarioFinalValido(), "Horário final inválido.");
        $this->assertNotEmpty($produto->getPromocao()['dias']);
        $this->assertTrue($produto->isIntervaloHorarioValido(), "Horários inválidos ou intervalo de tempo inferior a 15 minutos.");
    }
}
