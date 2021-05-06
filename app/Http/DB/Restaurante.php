<?php

namespace App\Http\DB;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class Restaurante
 * @package App\Http\Controllers\Restaurante
 */
class Restaurante
{

    public function inserir(array $input)
    {
        $dados =  json_decode($input['dados']);

        $path = $this->uploadFoto($input['foto']);

        $registro = DB::insert('insert into restaurantes (nome, endereco, foto) values (?, ?, ?)', [$dados->nome, $dados->endereco, $path]);

        if($registro < 1) {
            return false;
        }

        $this->inserir_funcionamento($dados->funcionamento, DB::getPdo()->lastInsertId());

        return true;
    }

    public function editar(array $input, int $id)
    {
        $dados = json_decode($input['dados']);

        $path = $this->uploadFoto($input['foto']);

        $restaurante = DB::update("update restaurantes set nome = '".$dados->nome."',
        endereco = '".$dados->endereco."', foto = '".$path."' where id = ? ", [$id]);

        $this->inserir_funcionamento($dados->funcionamento, $id);

        return true;
    }

    public function inserir_funcionamento(array $dados, int $id_restaurante)
    {
        DB::delete('delete from funcionamento_restaurantes where id_restaurante = ? ', [$id_restaurante]);

        foreach ($dados as $key => $value) {
            DB::insert('insert into funcionamento_restaurantes (id_restaurante, dias, horario_inicial, horario_final) values (?, ?, ?, ?)',
            [$id_restaurante, $value->dias, $value->horario_inicial, $value->horario_final]);
        }
    }

    public function show(int $id)
    {
        $data = DB::table('restaurantes')
        ->select(DB::raw('restaurantes.id, restaurantes.nome, restaurantes.endereco, restaurantes.foto,
        funcionamento_restaurantes.dias, funcionamento_restaurantes.horario_inicial, funcionamento_restaurantes.horario_final'))
        ->join('funcionamento_restaurantes', 'restaurantes.id', '=', 'funcionamento_restaurantes.id_restaurante')
        ->where('restaurantes.id', '=', $id)
        ->orderBy('restaurantes.id')
        ->get();

        return $data;
    }

    public function all()
    {
        $data = DB::table('restaurantes')
        ->select(DB::raw('restaurantes.id, restaurantes.nome, restaurantes.endereco, restaurantes.foto,
        funcionamento_restaurantes.dias, funcionamento_restaurantes.horario_inicial, funcionamento_restaurantes.horario_final'))
        ->join('funcionamento_restaurantes', 'restaurantes.id', '=', 'funcionamento_restaurantes.id_restaurante')
        ->orderBy('restaurantes.id')
        ->get();

        return $data;
    }

    public function remover(int $id)
    {
        if(!$this->getProdutoByIdRestaurante($id)) {
            return false;
        }

        DB::delete('delete from funcionamento_restaurantes where id_restaurante = ? ', [$id]);
        $resultado = DB::delete('delete from restaurantes where id = ? ', [$id]);

        if($resultado < 1) {
            return false;
        }

        return true;
    }

    //Verifica se existe produto atrelado ao restaurante especifico
    public function getProdutoByIdRestaurante(int $id_restaurante)
    {
        $data = DB::table('produtos')
        ->select(DB::raw('produtos.id'))
        ->where('produtos.id_restaurante', '=', $id_restaurante)
        ->get();

        if(count($data) > 0) {
            return false;
        }

        return true;
    }

    public function uploadFoto(object $arquivo)
    {
        $path = $arquivo->store('restaurantes','public');
        return $path;
    }

    public function removerUpload()
    {

    }

}
?>
