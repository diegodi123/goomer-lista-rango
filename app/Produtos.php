<?php

namespace App;


class Produtos
{
    private $nome;
    private $preco;
    private $id_categoria;
    private $id_restaurante;
    private $is_promocao;
    private $descricao;
    private $preco_promocional;
    private $promocao;

    public function __construct(string $nome, float $preco, int $id_categoria, int $id_restaurante,
    bool $is_promocao, string $descricao, float $preco_promocional, array $promocao)
    {
        $this->nome = $nome;
        $this->preco = $preco;
        $this->id_categoria = $id_categoria;
        $this->id_restaurante = $id_restaurante;
        $this->is_promocao = $is_promocao;
        $this->descricao = $descricao;
        $this->preco_promocional = $preco_promocional;
        $this->promocao = $promocao;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getPreco()
    {
        return $this->preco;
    }

    public function getIdCategoria()
    {
        return $this->id_categoria;
    }

    public function getIdRestaurante()
    {
        return $this->id_restaurante;
    }

    public function getIsPromocao()
    {
        return $this->is_promocao;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function getPrecoPromocional()
    {
        return $this->preco_promocional;
    }

    public function getPromocao()
    {
        return $this->promocao;
    }

    public function isArrayPromocao()
    {
        if(empty($this->promocao) && $this->is_promocao == true) {
            return false;
        }

        return true;
    }

    public function isHorarioInicialValido()
    {
        return preg_match('/^(0[0-9]|1[0-9]|2[0-3]):([0-5][0-9])/', $this->promocao['horario_inicial']) ? true : false;
    }

    public function isHorarioFinalValido()
    {
        return preg_match('/^(0[0-9]|1[0-9]|2[0-3]):([0-5][0-9])/', $this->promocao['horario_final']) ? true : false;
    }

    public function isIntervaloHorarioValido()
    {
        $horario_inicial = new \DateTime($this->promocao['horario_inicial']);
        $horario_final = new \DateTime($this->promocao['horario_final']);

        $diff = $horario_inicial->diff($horario_final);
        $minutos = ($diff->h * 60) + $diff->i;

        if($minutos < 14) {
            return false;
        }

        return true;
    }
}
