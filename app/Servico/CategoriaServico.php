<?php

namespace App\Servico;

use App\Models\CategoriaProduto;
use App\Models\Empresa;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriaServico implements IServico
{

    public function cadastrar(Request $requisicao) {
        
        try {
            $validador = Validator::make($requisicao->all(), [
                'descricao' => 'required|min:3|max:255|unique:categoria_produtos',
                'empresa_id' => 'required|numeric|min:1'
            ],
            [
                'descricao.required' => 'Informe a descrição da categoria!',
                'descricao.min' => 'A descrição da categoria deve possuir no mínimo 3 caracteres!',
                'descricao.max' => 'A descrição da categoria deve possuir no máximo 255 caracteres!',
                'descricao.unique' => 'Já existe uma categoria cadastrada para sua empresa com essa descrição!',
                'empresa_id.required' => 'Informe o id da empresa!',
                'empresa_id.numeric' => 'O id da empresa deve ser um valor numérico!',
                'empresa_id.min' => 'O id da empresa deve ser maior ou igual a 1!'
            ]);

            if ($validador->fails()) {

                return response()
                    ->json([
                        'msg' => 'Ocorreram erros de validação de dados!',
                        'dados' => $validador->errors(),
                        'ok' => false
                    ], 200);
            }

            $empresa = Empresa::find($requisicao->empresa_id);

            if (empty($empresa)) {

                return response()
                    ->json([
                        'msg' => 'Não existe uma empresa cadastrada no banco de dados com esse id!',
                        'dados' => null,
                        'ok' => false
                    ], 200);
            }

            if (!$empresa->get()->toArray()[0]['status']) {

                return response()
                    ->json([
                        'msg' => 'A empresa em questão não está com ativa!',
                        'dados' => null,
                        'ok' => false
                    ], 200);
            }

            $categoria = new CategoriaProduto();
            $categoria->descricao = $requisicao->descricao;
            $categoria->empresa_id = $requisicao->empresa_id;

            if ($categoria->save()) {

                return response()
                    ->json([
                        'msg' => 'Categoria de produto cadastrada com sucesso!',
                        'dados' => [
                            'id' => $categoria->id,
                            'descricao' => $categoria->descricao,
                            'status' => true,
                            'empresa' => $empresa->get()->toArray()[0]['nome']
                        ]
                    ], 201);
            } else {

                return response()
                    ->json([
                        'msg' => 'Ocorreu um erro ao tentar-se cadastrar a categoria no banco de dados!',
                        'dados' => null,
                        'ok' => false
                    ], 200);
            }

        } catch (Exception $e) {

            return response()
                ->json([
                    'msg' => 'Ocorreu um erro ao tentar-se cadastrar a categoria!' . $e->getMessage(),
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
                        'msg' => 'Informe o id da categoria de produto!',
                        'dados' => null,
                        'ok' => false
                    ], 200);
            }

            if (!is_numeric($id)) {

                return response()
                    ->json([
                        'msg' => 'O id da categoria deve ser um valor numérico!',
                        'dados' => null,
                        'ok' => false
                    ], 200);
            }

            $categoriaProduto = CategoriaProduto::find($id);

            if (empty($categoriaProduto)) {

                return response()
                    ->json([
                        'msg' => 'Não existe uma categoria cadastrada no banco de dados com esse id!',
                        'dados' => null,
                        'ok' => false
                    ], 200);
            }

            return response()
                ->json([
                    'msg' => 'Categoria encontrada com sucesso!',
                    'dados' => $categoriaProduto,
                    'ok' => false
                ], 200);
        } catch (Exception $e) {

            return response()
                ->json([
                    'msg' => 'Ocorreu um erro ao tentar-se buscar a categoria pelo id!',
                    'dados' => null,
                    'ok' => false
                ], 200);
        }

    }

    public function buscarTodasCategoriasEmpresa($empresaId) {

        try {

            if (empty($empresaId)) {

                return response()
                    ->json([
                        'msg' => 'Informe o id da empresa!',
                        'dados' => null, 
                        'ok' => false
                    ], 200);
            }

            if (!is_numeric($empresaId)) {

                return response()
                    ->json([
                        'msg' => 'O id empresa deve ser um valor numérico!',
                        'dados' => null,
                        'ok' => false
                    ], 200);
            }

            $empresa = Empresa::find($empresaId);

            if (!$empresa) {

                return response()
                    ->json([
                        'msg' => 'Não existe uma empresa cadastrada no banco de dados com esse id!',
                        'dados' => null,
                        'ok' => false
                    ], 200);
            }

            $categoriasEmpresa = $empresa->categoriasProduto()->get()->toArray();
            
            if (count($categoriasEmpresa) === 0) {

                return response()
                    ->json([
                        'msg' => 'A empresa não possui categorias de produto cadastradas no banco de dados!',
                        'dados' => [],
                        'ok' => true
                    ], 200);
            }

            return response()
                ->json([
                    'msg' => 'Categorias encontradas com sucesso!',
                    'dados' => $categoriasEmpresa,
                    'ok' => true
                ], 200);
        } catch (Exception $e) {

            return response()
                ->json([
                    'msg' => 'Ocorreu um erro ao tentar-se buscar as categorias de produtos da empresa!',
                    'dados' => null,
                    'ok' => false
                ], 200);
        }

    }
}