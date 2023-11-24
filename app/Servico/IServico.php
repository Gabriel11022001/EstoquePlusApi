<?php

namespace App\Servico;

use Illuminate\Http\Request;

interface IServico
{

    function cadastrar(Request $requisicao);
    
    function buscarPeloId($id);

    function editar(Request $requisicao);
}