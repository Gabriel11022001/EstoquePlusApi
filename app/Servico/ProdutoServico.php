<?php

namespace App\Servico;

use App\Models\Produto;
use Exception;
use Illuminate\Http\Request;

class ProdutoServico implements IServicoProduto
{

    public function cadastrar(Request $requisicao) {
        
        try {
            
        } catch (Exception $e) {
            
            return response()
                ->json([
                    'msg' => 'Ocorreu um erro ao tentar-se cadastrar o produto!',
                    'dados' => null,
                    'ok' => false
                ], 200);
        }

    }

    public function editar(Request $requisicao) {
        
    }

    public function buscarPeloId($id) {

        try {

            if (empty($id)) {

                return response()
                    ->json([
                        'msg' => 'Informe o id do produto!',
                        'dados' => null,
                        'ok' => false
                    ], 200);
            }

            if (!is_numeric($id)) {

                return response()
                    ->json([
                        'msg' => 'O id do produto deve ser um valor numérico!',
                        'dados' => null,
                        'ok' => false
                    ], 200);
            }

            $produto = Produto::find($id);
            var_dump($produto);
        } catch (Exception $e) {

            return response()
                ->json([
                    'msg' => 'Ocorreu um erro ao tentar-se buscar o produto pelo id!',
                    'dados' => null,
                    'ok' => false
                ], 200);
        }

    }

    public function buscarTodosProdutosEmpresa($idEmpresa) {
        
    }

    public function buscarPeloCodigoBarras($codigoBarras) {
        
    }
}