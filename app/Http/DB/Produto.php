<?php

namespace App\Http\DB;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class Produto
 * @package App\Http\Controllers\Produto
 */
class Produto
{

    public function inserir(array $input)
    {
        $dados = json_decode($input['dados']);

        $path = $this->uploadFoto($input['foto']);

        $registro = DB::insert('insert into produtos (id_restaurante, id_categoria, nome, preco, foto, is_promocao, descricao_promocao, preco_promocional)
         values (?, ?, ?, ?, ?, ?, ?, ?)', [$dados->id_restaurante, $dados->id_categoria, $dados->nome, $dados->preco, $path,
         $dados->is_promocao, $dados->descricao_promocao, ($dados->preco_promocional != "" ? $dados->preco_promocional : null)]);

        if($registro < 1) {
            return false;
        }

        $this->inserir_promocao($dados, DB::getPdo()->lastInsertId());

        return true;
    }

    public function editar(array $input, int $id)
    {
        $dados = json_decode($input['dados']);

        $this->removerUpload($id);

        $path = $this->uploadFoto($input['foto']);

        $produto = DB::update("update produtos set id_restaurante = '".$dados->id_restaurante."',id_categoria = '".$dados->id_categoria."',
        nome = '".$dados->nome."', preco = '".$dados->preco."', foto = '".$path."', is_promocao = ".$dados->is_promocao."
        , descricao_promocao = '".$dados->descricao_promocao."', preco_promocional = ".($dados->preco_promocional != "" ? $dados->preco_promocional : null)."
        where id = ? ", [$id]);

        $this->inserir_promocao($dados, $id);

        return true;
    }

    public function inserir_promocao(object $dados, int $id_produto)
    {
        if($dados->is_promocao) {
            DB::delete('delete from promocao_produtos where id_produto = ? ', [$id_produto]);

            foreach ($dados->promocao as $key => $value) {
                DB::insert('insert into promocao_produtos (id_produto, dia, horario_inicial, horario_final) values (?, ?, ?, ?)',
                [$id_produto, $value->dia, $value->horario_inicial, $value->horario_final]);
            }
        }
    }

    public function show(int $id)
    {
        $data = DB::table('produtos')
        ->select(DB::raw('produtos.id, produtos.nome as nome_produto, produtos.preco, produtos.foto, produtos.id_categoria,
        produtos.id_restaurante, produtos.is_promocao, produtos.descricao_promocao, produtos.preco_promocional,
        promocao_produtos.dia, promocao_produtos.horario_inicial, promocao_produtos.horario_final,
        categorias.nome as nome_categoria, restaurantes.nome as nome_restaurante'))
        ->join('categorias', 'produtos.id_categoria', '=', 'categorias.id')
        ->join('restaurantes', 'produtos.id_restaurante', '=', 'restaurantes.id')
        ->leftJoin('promocao_produtos', 'produtos.id', '=', 'promocao_produtos.id_produto')
        ->where('produtos.id', '=', $id)
        ->orderBy('produtos.id')
        ->orderBy('promocao_produtos.id')
        ->get();

        return $data;
    }

    public function all(int $id_restaurante)
    {
        $data = DB::table('produtos')
        ->select(DB::raw('produtos.id, produtos.nome as nome_produto, produtos.preco, produtos.foto, produtos.id_categoria,
        produtos.id_restaurante, produtos.is_promocao, produtos.descricao_promocao, produtos.preco_promocional,
        promocao_produtos.dia, promocao_produtos.horario_inicial, promocao_produtos.horario_final,
        categorias.nome as nome_categoria, restaurantes.nome as nome_restaurante'))
        ->join('categorias', 'produtos.id_categoria', '=', 'categorias.id')
        ->join('restaurantes', 'produtos.id_restaurante', '=', 'restaurantes.id')
        ->leftJoin('promocao_produtos', 'produtos.id', '=', 'promocao_produtos.id_produto')
        ->where('produtos.id_restaurante', '=', $id_restaurante)
        ->orderBy('produtos.id')
        ->orderBy('promocao_produtos.id')
        ->get();

        return $data;
    }

    public function remover(int $id)
    {
        DB::delete('delete from promocao_produtos where id_produto = ? ', [$id]);
        $resultado = DB::delete('delete from produtos where id = ? ', [$id]);

        if($resultado < 1) {
            return false;
        }

        $this->removerUpload($id);

        return true;
    }

    public function uploadFoto(object $arquivo)
    {
        $path = $arquivo->store('produtos','public');
        return $path;
    }

    public function removerUpload(int $id)
    {
        $data = $this->show($id);

        Storage::disk('public')->delete($data[0]->foto);
    }
}
?>
