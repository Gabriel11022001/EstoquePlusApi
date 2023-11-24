<?php

namespace App\Servico;

use App\Models\Administrador;
use App\Models\Empresa;
use App\Models\TelefoneUsuario;
use App\Models\Usuario;
use App\Models\Vendedor;
use App\Utils\ValidaCamposCadastroUsuario;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsuarioServico implements IServico
{

    public function cadastrar(Request $requisicao) {
        DB::beginTransaction();

        try {
            $tipoUsuarioCadastrar = $requisicao->tipo_usuario;
            $nome = $requisicao->nome;
            $email = $requisicao->email;
            $telefones = $requisicao->telefones;
            $cpf = $requisicao->cpf;
            $senha = $requisicao->senha;
            $senhaConfirmacao = $requisicao->senha_confirmacao;
            $dataNascimento = $requisicao->data_nascimento;
            $empresaId = $requisicao->empresa_id;
            $errosCampos = ValidaCamposCadastroUsuario::validarCamposCadastroUsuario([
                'nome' => $nome,
                'telefones' => $telefones
            ]);

            if (count($errosCampos) > 0) {

                return response()->json([
                    'msg' => 'Ocorreram erros de validação de dados!',
                    'dados' => $errosCampos,
                    'ok' => false
                ], 200);
            }

            // validando se existe outro usuário cadastrado com o e-mail informado
            $usuarioComEmailInformado = Usuario::where('email', $email)->get()->toArray();

            if ($usuarioComEmailInformado) {

                return response()
                    ->json([
                        'msg' => 'Já existe um perfil cadastrado com esse e-mail, informe outro e-mail!',
                        'dados' => null,
                        'ok' => false
                    ], 200);
            }

            // validando se já existe outro usuário cadastrado com o cpf informado
            $usuarioCadastradoComCpfInformado = Usuario::where('cpf', $cpf)->get()->toArray();

            if ($usuarioCadastradoComCpfInformado) {

                return response()
                    ->json([
                        'msg' => 'Já existe um perfl cadastrado com o cpf informado, informe outro cpf!',
                        'dados' => null,
                        'ok' => false
                    ], 200);
            }

            // validando se já existe outro usuário cadastrado com algum telefone informado
            foreach ($telefones as $telefone) {
                $telefoneUsuarioEncontrado = TelefoneUsuario::where('numero_telefone', $telefone['numero_telefone'])->get()->toArray();
                
                if (!empty($telefoneUsuarioEncontrado)) {
                    DB::rollBack();

                    return response()->json([
                        'msg' => 'Já existe outro usuário cadastrado com o número ' . $telefone['ddi'] . '(' . $telefone['ddd'] . ')' . ' ' . $telefone['numero_telefone'] . ', informe outro número!',
                        'dados' => null,
                        'ok' => false
                    ], 200);
                }

            }

            // validando se existe uma empresa cadastrada com o id informado
            $empresa = Empresa::find($empresaId);
            if (!$empresa) {

                return response()->json([
                    'msg' => 'Não existe uma empresa cadastrada com o id informado!',
                    'dados' => null,
                    'ok' => false
                ], 200);
            }

            $usuario = new Usuario();
            $usuario->nome = mb_strtoupper($nome);
            $usuario->email = $email;
            $usuario->cpf = $cpf;
            $dataNascimentoCadastrar = new DateTime($dataNascimento);
            $usuario->data_nascimento = $dataNascimentoCadastrar->format('Y-m-d');
            $usuario->senha = md5($senha);
            $usuario->empresa_id = $empresaId;

            if ($usuario->save()) {

                // usuário cadastrado com sucesso, cadastrar os telefones
                foreach ($telefones as $telefone) {
                    $telefoneCadastrar = new TelefoneUsuario();
                    $telefoneCadastrar->ddi = $telefone['ddi'];
                    $telefoneCadastrar->ddd = $telefone['ddd'];
                    $telefoneCadastrar->numero_telefone = $telefone['numero_telefone'];
                    $telefoneCadastrar->usuario_id = $usuario->id;

                    if (!$telefoneCadastrar->save()) {
                        DB::rollBack();

                        return response()->json([
                            'msg' => 'Ocorreu um erro ao tentar-se cadastrar o telefone do usuário!',
                            'dados' => null,
                            'ok' => false
                        ], 200);
                    }
                }

                if ($tipoUsuarioCadastrar === 'admin') {
                    // cadastrar o admin
                    $admin = new Administrador();
                    $admin->usuario_id = $usuario->id;

                    if (!$admin->save()) {
                        DB::rollBack();

                        return response()->json([
                            'msg' => 'Ocorreu um erro ao tentar-se cadastrar o usuário!',
                            'dados' => null,
                            'ok' => false
                        ], 200);
                    }

                } else {
                    // cadastrar o vendedor
                    $vendedor = new Vendedor();
                    $vendedor->usuario_id = $usuario->id;

                    if (!$vendedor->save()) {
                        DB::rollBack();

                        return response()->json([
                            'msg' => 'Ocorreu um erro ao tentar-se cadastrar o usuário!',
                            'dados' => null,
                            'ok' => false
                        ], 200);
                    }

                }

                DB::commit();

                return response()->json([
                    'msg' => 'Usuário cadastrado com sucesso!',
                    'dados' => [
                        'id' => $usuario->id,
                        'nome' => $nome,
                        'email' => $email,
                        'cpf' => $cpf,
                        'data_nascimento' => $dataNascimentoCadastrar->format('d-m-Y'),
                        'tipo_usuario' => $tipoUsuarioCadastrar,
                        'telefones' => $telefones,
                        'empresa' => $empresa
                    ]
                ], 201);
            } else {

                return response()->json([
                    'msg' => 'Ocorreu um erro ao tentar-se cadastrar o usuário!',
                    'dados' => null,
                    'ok' => false
                ], 200);
            }

        } catch (Exception $e) {
            DB::rollBack();

            return response()
                ->json([
                    'msg' => 'Ocorreu um erro ao tentar-se cadastrar o usuário!' . $e->getMessage(),
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
                        'msg' => 'Informe o id do usuário!',
                        'dados' => null,
                        'ok' => false
                    ], 200);
            }

            $usuario = Usuario::find($id);

            if (!$usuario) {

                return response()
                    ->json([
                        'msg' => 'Não existe um usuário cadastrado no banco de dados com esse id!',
                        'dados' => null,
                        'ok' => false
                    ], 200);
            }

            $vendedor = Vendedor::where('usuario_id', $id)->get()->toArray();

            if (!empty($vendedor)) {
                $usuario['vendedor_id'] = $vendedor[0]['id'];
            }

            $administrador = Administrador::where('usuario_id', $id)->get()->toArray();

            if (!empty($administrador)) {
                $usuario['administrador_id'] = $administrador[0]['id'];
            }

            return response()
                ->json([
                    'msg' => 'Usuário encontrado com sucesso!',
                    'dados' => $usuario,
                    'ok' => true
                ], 200);
        } catch (Exception $e) {

            return response()
                ->json([
                    'msg' => 'Ocorreu um erro ao tentar-se buscar o usuário pelo id!',
                    'dados' => null,
                    'ok' => false
                ], 200);
        }

    }

    public function buscarTodosUsuarios($empresaId) {

        try {

            if (empty($empresaId)) {

                return response()->json([
                    'msg' => 'Informe o id da empresa!',
                    'dados' => null,
                    'ok' => false
                ], 200);
            }
            
            if (!Empresa::find($empresaId)) {

                return response()->json([
                    'msg' => 'Não existe uma empresa cadastrada no banco de dados com esse id!',
                    'dados' => null,
                    'ok' => false
                ], 200);
            }

            $usuarios = Usuario::select('id', 'nome', 'email')
                ->where('empresa_id', $empresaId)
                ->get()
                ->toArray();

            if (empty($usuarios)) {

                return response()->json([
                    'msg' => 'A empresa não possui usuários cadastrados!',
                    'dados' => [],
                    'ok' => true
                ], 200);
            }

            $usuariosRetornar = [];

            foreach ($usuarios as $usuario) {
                $tipoUsuario = '';

                if (!empty(Vendedor::where('usuario_id', $usuario['id'])->get()->toArray())) {
                    $tipoUsuario = 'vendedor';
                } else {
                    $tipoUsuario = 'admin';
                }

                $usuariosRetornar[] = [
                    'id' => $usuario['id'],
                    'nome' => $usuario['nome'],
                    'email' => $usuario['email'],
                    'tipo_usuario' => $tipoUsuario 
                ];
            }

            return response()->json([
                'msg' => 'Usuários encontrados com sucesso!',
                'dados' => $usuariosRetornar,
                'ok' => true
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                'msg' => 'Ocorreu um erro ao tentar-se buscar todos os usuários cadastrados no banco de dados!',
                'dados' => null,
                'ok' => false
            ], 200);
        }

    }
}
