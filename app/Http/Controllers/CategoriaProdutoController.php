<?php

namespace App\Http\Controllers;

use App\Servico\CategoriaServico;
use Illuminate\Http\Request;

class CategoriaProdutoController extends Controller
{
    private $categoriaProdutoServico;

    public function __construct(CategoriaServico $categoriaProdutoServico) {
        $this->categoriaProdutoServico = $categoriaProdutoServico;
    }

    public function cadastrarCategoriaProduto(Request $requisicao) {

        return $this->categoriaProdutoServico->cadastrar($requisicao);
    }

    public function buscarTodasCategoriasEmpresa($empresaId) {

        return $this->categoriaProdutoServico->buscarTodasCategoriasEmpresa($empresaId);
    }

    public function buscarCategoriaProdutoPeloId($id) {

        return $this->categoriaProdutoServico->buscarPeloId($id);
    }
}
