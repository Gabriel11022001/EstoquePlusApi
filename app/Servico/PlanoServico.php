<?php

namespace App\Servico;

use App\Models\Plano;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlanoServico implements IServico
{

    public function cadastrar(Request $requisicao) {
        
        try {
            $validacao = Validator::make($requisicao->all(), [
                'descricao' => 'required|unique:planos',
                'preco_base' => 'required|min:0|numeric',
                'numero_maximo_admin' => 'nullable|numeric|min:1',
                'numero_maximo_vendedores' => 'nullable|numeric|min:1',
                'numero_maximo_fornecedores' => 'nullable|numeric|min:1'
            ],
            [
                'descricao.required' => 'A descrição é um campo obrigatório!',
                'descricao.unique' => 'Já existe um plano com esse nome, informe outro nome para o plano!',
                'preco_base.required' => 'O preço base do plano é um campo obrigatório!',
                'preco_base.min' => 'O preço base não deve ser menor que R$0.00!',
                'preco_base.numeric' => 'O preço base do plano deve ser um valor numérico!',
                'numero_maximo_admin.min' => 'O número máximo de administradores deve ser maior que 0!',
                'numero_maximo_vendedores.min' => 'O número máximo de vendedores deve ser maior que 0!',
                'numero_maximo_fornecedores.min' => 'O número máximo de fornecedores deve ser maior que 0!'
            ]);

            if ($validacao->fails()) {

                return response()
                    ->json([
                        'msg' => 'Ocorreram erros de validação de dados!',
                        'dados' => $validacao->errors(),
                        'ok' => false
                    ], 200);
            }

            $plano = new Plano();
            $plano->descricao = $requisicao->descricao;
            $plano->preco_base = $requisicao->preco_base;
            $plano->numero_maximo_admin = $requisicao->numero_maximo_admin;
            $plano->numero_maximo_vendedores = $requisicao->numero_maximo_vendedores;
            $plano->numero_maximo_fornecedores = $requisicao->numero_maximo_fornecedores;
            $plano->percentual_desconto_promocao = 0;

            if (!$plano->save()) {

                return response()->json([
                    'msg' => 'Ocorreu um erro ao tanter-se cadastrar o plano!',
                    'dados' => null,
                    'ok' => false
                ], 200);
            }

            return response()->json([
                'msg' => 'Plano cadastrado com sucesso!',
                'dados' => $plano->toArray(),
                'ok' => true
            ], 201);
        } catch (Exception $e) {

            return response()
                ->json([
                    'msg' => 'Ocorreu um erro ao tanter-se cadastrar o plano!' . $e->getMessage(),
                    'dados' => null,
                    'ok' => false
                ], 200);
        }

    }

    public function editar(Request $requisicao) {
        
    }

    public function buscarPeloId($id) {
        
    }

    public function buscarTodosPlanos() {

    }
}