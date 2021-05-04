<?php

use App\Categorias;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categorias = array('Pizzas','Hambúrgueres','Refrigerante','Suco','Cerveja','Sobremesa');

        foreach ($categorias as $c => $categoria) {
          Categorias::create(
            [
              'nome' => $categoria
            ]
          );
        }
    }
}
