<?php

namespace App\Http\Controllers;

use App\Servico\ProdutoServico;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    private $produtoServico;

    public function __construct(ProdutoServico $produtoServico) {
        $this->produtoServico = $produtoServico;
    }

    public function buscarProdutoPeloId($id) {

        return $this->produtoServico->buscarPeloId($id);
    }
}
