<?php

namespace App;


class Restaurantes
{
    private $nome;
    private $endereco;
    private $funcionamento;

    public function __construct(string $nome, string $endereco, array $funcionamento)
    {
        $this->nome = $nome;
        $this->endereco = $endereco;
        $this->funcionamento = $funcionamento;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getEndereco()
    {
        return $this->endereco;
    }

    public function getFuncionamento()
    {
        return $this->funcionamento;
    }

    public function isArrayFuncionamento()
    {
        if(!is_array($this->funcionamento)) {
            return false;
        }

        return true;
    }

    public function isHorarioInicialValido()
    {
        return preg_match('/^(0[0-9]|1[0-9]|2[0-3]):([0-5][0-9])/', $this->funcionamento['horario_inicial']) ? true : false;
    }

    public function isHorarioFinalValido()
    {
        return preg_match('/^(0[0-9]|1[0-9]|2[0-3]):([0-5][0-9])/', $this->funcionamento['horario_final']) ? true : false;
    }
}
