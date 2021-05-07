<?php

namespace App\Http\Controllers;

use App\Http\DB\Produto;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProdutoController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function inserir(Request $request)
    {
        if(!$this->validateDadosProduto($request)) {
            return response()->json(['msg' => 'Preencha corretamente todos os dados do produto.'], 403);
        }

        if(!$this->validateDadosPromocao($request)) {
            return response()->json(['msg' => 'Preencha corretamente todos os dados da promoção do produto.'], 403);
        }

        if(!$this->validateIntervaloHorario($request)) {
            return response()->json(['msg' => 'O intervalo entre o horário inicial e o horário final, deve ser no minimo de 15 minutos.'], 403);
        }

        if(!$this->validateImagem($request)) {
            return response()->json(['msg' => 'Conteúdo vazio ou formato inválido.'], 403);
        }

        if(!(new Produto())->inserir($request->all())) {
            return response()->json(['msg' => 'Erro ao salvar registro.'], 400);
        }

        return response()->json(['msg' => 'Registro salvo com sucesso.'], 201);
    }

    public function validateDadosProduto(Request $request)
    {
        $dados = json_decode($request->input('dados'));
        $validator = Validator::make((array) $dados, [
            'nome' => 'required|max:100',
            'id_restaurante' => 'required',
            'id_categoria' => 'required',
            'preco' => 'required',
            'is_promocao' => 'required|boolean'
        ]);

        if($validator->fails()) {
            return false;
        }

        return true;
    }

    public function validateDadosPromocao(Request $request)
    {
        $input = json_decode($request->input('dados'));
        $dados = json_decode(json_encode($input), true);

        if($dados['promocao'] != "") {
            $validator = Validator::make((array) $dados, [
                'promocao.*.dia' => 'required|date|date_format:Y-m-d',
                'promocao.*.horario_inicial' => 'required|date_format:H:i|max:5',
                'promocao.*.horario_final' => 'required|date_format:H:i|max:5'
            ]);

            if($validator->fails()) {
                return false;
            }
        }

        return true;
    }

    public function validateIntervaloHorario(Request $request)
    {
        $dados =  json_decode($request->input('dados'));
        if($dados->promocao != "") {
            foreach ($dados->promocao as $key => $promocao) {
                if(strtotime($promocao->horario_inicial) > strtotime($promocao->horario_final)) {
                    return false;
                }

                if(!$this->horaParaMinutos($promocao->horario_inicial, $promocao->horario_final)) {
                    return false;
                }

            }
        }

        return true;
    }

    public function horaParaMinutos(string $horario1, string $horario2)
    {
        $horario_inicial = new \DateTime($horario1);
        $horario_final = new \DateTime($horario2);

        $diff = $horario_inicial->diff($horario_final);
        $minutos = ($diff->h * 60) + $diff->i;

        if($minutos < 14) {
            return false;
        }

        return true;
    }

    public function validateImagem(Request $request)
    {
        $validator = $request->validate([
            'foto' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048|dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000',
        ]);

        return true;
    }

        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all(int $id_restaurante)
    {
        $data = (new Produto())->all($id_restaurante);

        if(count($data) < 1) {
            return response()->json(['msg' => 'Nenhum registro encontrado.'], 204);
        }

        $dados = array();
        foreach ($data as $key => $produto) {
            $dados[$produto->id]['id'] = $produto->id;
            $dados[$produto->id]['nome_produto'] = $produto->nome_produto;
            $dados[$produto->id]['foto'] = $produto->foto;
            $dados[$produto->id]['preco'] = $produto->preco;
            $dados[$produto->id]['id_categoria'] = $produto->id_categoria;
            $dados[$produto->id]['nome_categoria'] = $produto->nome_categoria;
            $dados[$produto->id]['id_restaurante'] = $produto->id_restaurante;
            $dados[$produto->id]['nome_restaurante'] = $produto->nome_restaurante;
            $dados[$produto->id]['is_promocao'] = $produto->is_promocao;
            if($produto->is_promocao) {
                $dados[$produto->id]['descricao_promocao'] = $produto->descricao_promocao;
                $dados[$produto->id]['preco_promocional'] = $produto->preco_promocional;
                $dados[$produto->id]['promocao'][$key]['dia'] = $this->getDiaSemana($produto->dia);
                $dados[$produto->id]['promocao'][$key]['horario_inicial'] = $produto->horario_inicial;
                $dados[$produto->id]['promocao'][$key]['horario_final'] = $produto->horario_final;
            }
        }

        return response()->json($dados, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function visualizar(int $id)
    {
        $data = (new Produto())->show($id);

        if(count($data) < 1) {
            return response()->json(['msg' => 'Nenhum registro encontrado.'], 204);
        }

        $dados = array();
        foreach ($data as $key => $produto) {
            $dados['id'] = $produto->id;
            $dados['nome_produto'] = $produto->nome_produto;
            $dados['foto'] = $produto->foto;
            $dados['preco'] = $produto->preco;
            $dados['id_categoria'] = $produto->id_categoria;
            $dados['nome_categoria'] = $produto->nome_categoria;
            $dados['id_restaurante'] = $produto->id_restaurante;
            $dados['nome_restaurante'] = $produto->nome_restaurante;
            $dados['is_promocao'] = $produto->is_promocao;
            $dados['descricao_promocao'] = $produto->descricao_promocao;
            $dados['preco_promocional'] = $produto->preco_promocional;
            $dados['promocao'][$key]['dia'] = $this->getDiaSemana($produto->dia);
            $dados['promocao'][$key]['horario_inicial'] = $produto->horario_inicial;
            $dados['promocao'][$key]['horario_final'] = $produto->horario_final;
        }

        return response()->json($dados, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editar(Request $request, int $id)
    {
        if(!$this->validateDadosProduto($request)) {
            return response()->json(['msg' => 'Preencha corretamente todos os dados do produto.'], 403);
        }

        if(!$this->validateDadosPromocao($request)) {
            return response()->json(['msg' => 'Preencha corretamente todos os dados da promoção do produto.'], 403);
        }

        if(!$this->validateIntervaloHorario($request)) {
            return response()->json(['msg' => 'O intervalo entre o horário inicial e o horário final, deve ser no minimo de 15 minutos.'], 403);
        }

        if(!(new Produto())->editar($request->all(), $id)) {
            return response()->json(['msg' => 'Erro ao alterar registro.'], 400);
        }

        return response()->json(['msg' => 'Registro alterado com sucesso.'], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deletar(int $id)
    {
        if(!(new Produto())->remover($id)) {
            return response()->json(['msg' => 'Erro ao remover registro.'], 400);
        }

        return response()->json(['msg' => 'Registro removido com sucesso.'], 201);
    }

    public function getDiaSemana(string $data)
    {
        $diasemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado');

        $indice = date('w', strtotime($data));

        return $diasemana[$indice];
    }
}
