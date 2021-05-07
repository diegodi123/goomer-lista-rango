<?php

namespace App\Http\Controllers;

use App\Http\DB\Restaurante;
use App\Http\DB\Produto;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class RestauranteController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function inserir(Request $request)
    {
        if(!$this->validateDadosRestaurante($request)) {
            return response()->json(['msg' => 'Preencha corretamente todos os dados do restaurante.'], 403);
        }

        if(!$this->validateIntervaloHorario($request)) {
            return response()->json(['msg' => 'O intervalo entre o horário inicial e o horário final, deve ser no minimo de 15 minutos.'], 403);
        }

        if(!$this->validateImagem($request)) {
            return response()->json(['msg' => 'Conteúdo vazio ou formato inválido.'], 403);
        }

        if(!(new Restaurante())->inserir($request->all())) {
            return response()->json(['msg' => 'Erro ao salvar registro.'], 400);
        }

        return response()->json(['msg' => 'Registro salvo com sucesso.'], 201);
    }

    public function validateDadosRestaurante(Request $request)
    {
        $input = json_decode($request->input('dados'));
        $dados = json_decode(json_encode($input), true); //converte matriz de objeto em array de forma recursiva

        $validator = Validator::make($dados, [
            'nome' => 'required|max:100',
            'endereco' => 'required|max:150',
            'funcionamento.*.dias' => 'required',
            'funcionamento.*.horario_inicial' => 'required|date_format:H:i|max:5',
            'funcionamento.*.horario_final' => 'required|date_format:H:i|max:5'
        ]);

        if($validator->fails()) {
            return false;
        }

        return true;
    }

    public function validateIntervaloHorario(Request $request)
    {
        $dados = json_decode($request->input('dados'));

        if($dados->funcionamento != "") {
            foreach ($dados->funcionamento as $key => $funcionamento) {
                if(strtotime($funcionamento->horario_inicial) > strtotime($funcionamento->horario_final)) {
                    return false;
                }

                if(!$this->horaParaMinutos($funcionamento->horario_inicial, $funcionamento->horario_final)) {
                    return false;
                }

            }
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

    public function horaParaMinutos(string $horario1, string $horario2)
    {
        $horario_inicial = new \DateTime($horario1);
        $horario_final = new \DateTime($horario2);

        $diff = $horario_inicial->diff($horario_final);
        $minutos = ($diff->h * 60) + $diff->i;

        if($minutos < 15) {
            return false;
        }

        return true;
    }

        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        $data = (new Restaurante())->all();

        if(count($data) < 1) {
            return response()->json(['msg' => 'Nenhum registro encontrado.'], 204);
        }

        $dados = array();
        foreach ($data as $key => $restaurante) {
            $dados[$restaurante->id]['id'] = $restaurante->id;
            $dados[$restaurante->id]['nome'] = $restaurante->nome;
            $dados[$restaurante->id]['foto'] = $restaurante->foto;
            $dados[$restaurante->id]['endereco'] = $restaurante->endereco;
            $dados[$restaurante->id]['funcionamento'][$key]['dias'] = $restaurante->dias;
            $dados[$restaurante->id]['funcionamento'][$key]['horario_inicial'] = $restaurante->horario_inicial;
            $dados[$restaurante->id]['funcionamento'][$key]['horario_final'] = $restaurante->horario_final;
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
        $data = (new Restaurante())->show($id);

        if(count($data) < 1) {
            return response()->json(['msg' => 'Nenhum registro encontrado.'], 204);
        }

        $dados = array();
        foreach ($data as $key => $restaurante) {
            $dados['id'] = $restaurante->id;
            $dados['nome'] = $restaurante->nome;
            $dados['foto'] = $restaurante->foto;
            $dados['endereco'] = $restaurante->endereco;
            $dados['funcionamento'][$key]['dias'] = $restaurante->dias;
            $dados['funcionamento'][$key]['horario_inicial'] = $restaurante->horario_inicial;
            $dados['funcionamento'][$key]['horario_final'] = $restaurante->horario_final;
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
        if(!$this->validateDadosRestaurante($request)) {
            return response()->json(['msg' => 'Preencha corretamente todos os dados do restaurante.'], 403);
        }

        if(!$this->validateIntervaloHorario($request)) {
            return response()->json(['msg' => 'O intervalo entre o horário inicial e o horário final, deve ser no minimo de 15 minutos.'], 403);
        }

        if(!$this->validateImagem($request)) {
            return response()->json(['msg' => 'Conteúdo vazio ou formato inválido.'], 403);
        }

        if(!(new Restaurante())->editar($request->all(), $id)) {
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
        if(!(new Restaurante())->remover($id)) {
            return response()->json(['msg' => 'Erro ao remover registro.'], 400);
        }

        return response()->json(['msg' => 'Registro removido com sucesso.'], 201);
    }
}
