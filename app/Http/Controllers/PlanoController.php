<?php

namespace App\Http\Controllers;

use App\Servico\PlanoServico;
use Illuminate\Http\Request;

class PlanoController extends Controller
{
    private $planoServico;

    public function __construct(PlanoServico $planoServico) {
        $this->planoServico = $planoServico;
    }

    public function cadastrarPlano(Request $requisicao) {

        return $this->planoServico->cadastrar($requisicao);
    }
}
