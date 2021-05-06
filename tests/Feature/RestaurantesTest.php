<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Restaurantes;

class RestaurantesTest extends TestCase
{
    public function testAtributosRestaurante()
    {
        $this->assertClassHasAttribute('nome', Restaurantes::class);
        $this->assertClassHasAttribute('endereco', Restaurantes::class);
        $this->assertClassHasAttribute('funcionamento', Restaurantes::class);
    }

    public function testRestauranteEmpty()
    {
        $funcionamento = array(
            'dias' => 'Segunda a sexta',
            'horario_inicial' => '11:00',
            'horario_final' => '22:00');

        $restaurante = new Restaurantes('BK', 'shopping', $funcionamento);

        $this->assertTrue($restaurante->isArrayFuncionamento());
        $this->assertNotEmpty($restaurante->getNome());
        $this->assertNotEmpty($restaurante->getEndereco());
    }

    public function testFuncionamentoRestaurante()
    {
        $funcionamento = array(
            'dias' => 'Segunda a segunda',
            'horario_inicial' => '10:30',
            'horario_final' => '16:00');

        $restaurante = new Restaurantes('Caipirão', 'Avenida Dom Pedro II', $funcionamento);

        $this->assertTrue($restaurante->isHorarioInicialValido());
        $this->assertTrue($restaurante->isHorarioFinalValido());
        $this->assertNotEmpty($restaurante->getFuncionamento()['dias']);
    }
}
