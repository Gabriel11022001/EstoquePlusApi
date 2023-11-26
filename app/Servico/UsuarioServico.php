<?php

namespace App\Servico;

use App\Models\Administrador;
use App\Models\Empresa;
use App\Models\Plano;
use App\Models\TelefoneUsuario;
use App\Models\Usuario;
use App\Models\Vendedor;
use App\Utils\ObterDataLimiteContratacaoPlano;
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

    public function registrarse(Request $requisicao) {
        DB::beginTransaction();

        try {
            $errosCampos = ValidaCamposCadastroUsuario::validarCamposRegistrarse([
                'nome' => $requisicao->nome,
                'telefone' => $requisicao->telefone['numero_telefone'],
                'ddi' => $requisicao->telefone['ddi'],
                'ddd' => $requisicao->telefone['ddd'],
                'email' => $requisicao->email,
                'data_nascimento' => $requisicao->data_nascimento,
                'cpf' => $requisicao->cpf,
                'empresa' => $requisicao->empresa,
                'senha' => $requisicao->senha,
                'senha_confirmacao' => $requisicao->senha_confirmacao
            ]);
            
            if (count($errosCampos) > 0) {

                return response()->json([
                    'msg' => 'Ocorreram erros de validação de dados!',
                    'dados' => $errosCampos,
                    'ok' => false
                ], 200);
            }
            
            $empresa = Empresa::where('cnpj', $requisicao->empresa['cnpj'])->get()->toArray();

            if (!empty($empresa)) {

                return response()
                    ->json([
                        'msg' => 'Já existe uma empresa cadastrada com esse cnpj, caso você seja um funcionário dessa empresa, solicite ao administrador que o cadastre no sistema!',
                        'dados' => null,
                        'ok' => false
                    ], 200);
            }

            $plano = Plano::find($requisicao->empresa['plano_id'])->get()->toArray();

            if (empty($plano)) {
                DB::rollBack();

                return response()->json([
                    'msg' => 'Não existe um plano cadastrado com esse id!',
                    'dados' => null,
                    'ok' => false
                ], 200);
            }

            if (!$plano[0]['status']) {
                DB::rollBack();

                return response()->json([
                    'msg' => 'O plano que vocês está tentando contratar não está ativo!',
                    'dados' => null,
                    'ok' => false
                ], 200);
            }

            $empresaCadastrar = new Empresa();
            $empresaCadastrar->nome = $requisicao->empresa['nome'];
            $empresaCadastrar->ddi = $requisicao->empresa['ddi'];
            $empresaCadastrar->ddd = $requisicao->empresa['ddd'];
            $empresaCadastrar->telefone = $requisicao->empresa['telefone'];
            $empresaCadastrar->cnpj = $requisicao->empresa['cnpj'];
            $empresaCadastrar->email = $requisicao->empresa['email'];
            $empresaCadastrar->cep = $requisicao->empresa['cep'];
            $empresaCadastrar->endereco = $requisicao->empresa['endereco'];
            $empresaCadastrar->bairro = $requisicao->empresa['bairro'];
            $empresaCadastrar->cidade = $requisicao->empresa['cidade'];
            $empresaCadastrar->uf = $requisicao->empresa['uf'];
            $empresaCadastrar->numero = $requisicao->empresa['numero'];
            $empresaCadastrar->plano_id = $requisicao->empresa['plano_id'];
            $dataContratacaoPlano = new DateTime('now');
            $dataLimiteContratacaoPlano = ObterDataLimiteContratacaoPlano::obterDataLimite($dataContratacaoPlano, $requisicao->empresa['tempo_contratacao_plano']);
            $empresaCadastrar->data_contratacao_plano = $dataContratacaoPlano->format('Y-m-d H:i:s');
            $empresaCadastrar->data_limite_contrato_plano = $dataLimiteContratacaoPlano->format('Y-m-d H:i:s');
            $empresaCadastrar->tempo_contratacao_plano = $requisicao->empresa['tempo_contratacao_plano'];

            if (!$empresaCadastrar->save()) {
                DB::rollBack();

                return response()->json([
                    'msg' => 'Ocorreu um erro ao tentar-se registrar, por gentileza, tente novamente!',
                    'dados' => null,
                    'ok' => false
                ], 200);
            }

            if (!empty(Usuario::where('cpf', $requisicao->cpf)->get()->toArray())) {
                DB::rollBack();

                return response()->json([
                    'msg' => 'Já existe um perfil cadastrado com esse cpf, informe outro cpf!',
                    'dados' => null,
                    'ok' => false
                ], 200);
            }

            if (!empty(Usuario::where('email', $requisicao->email)->get()->toArray())) {
                DB::rollBack();

                return response()->json([
                    'msg' => 'Já existe um perfil cadastrado com esse e-mail, informe outro e-mail!',
                    'dados' => null,
                    'ok' => false
                ], 200);
            }   

            $usuario = new Usuario();
            $usuario->nome = mb_strtoupper($requisicao->nome);
            $usuario->email = $requisicao->email;
            $usuario->cpf = $requisicao->cpf;
            $usuario->senha = md5($requisicao->senha);
            $usuario->empresa_id = $empresaCadastrar->id;
            $dataNascimentoCadastrar = new DateTime($requisicao->data_nascimento);
            $usuario->data_nascimento = $dataNascimentoCadastrar->format('Y-m-d');

            if (!$usuario->save()) {
                DB::rollBack();

                return response()->json([
                    'msg' => 'Ocorreu um erro ao tentar-se registrar, por gentileza, tente novamente!',
                    'dados' => null,
                    'ok' => false
                ], 200);
            }

            if (!empty(TelefoneUsuario::where('numero_telefone', $requisicao->telefone['numero_telefone'])->get()->toArray())) {
                DB::rollBack();

                return response()->json([
                    'msg' => 'Informe outro telefone!',
                    'dados' => null,
                    'ok' => false
                ], 200);
            }

            $telefone = new TelefoneUsuario();
            $telefone->ddi = $requisicao->telefone['ddi'];
            $telefone->ddd = $requisicao->telefone['ddd'];
            $telefone->numero_telefone = $requisicao->telefone['numero_telefone'];
            $telefone->usuario_id = $usuario->id;

            if (!$telefone->save()) {
                DB::rollBack();

                return response()->json([
                    'msg' => 'Ocorreu um erro ao tentar-se registrar, por gentileza, tente novamente!',
                    'dados' => null,
                    'ok' => false
                ], 200);
            }

            DB::commit();

            return response()->json([
                'msg' => 'Seu perfil foi cadastrado com sucesso, você será redirecionado em instantes para a tela de login!',
                'dados' => [
                    'id' => $usuario->id,
                    'nome' => $usuario->nome,
                    'email' => $usuario->email,
                    'cpf' => $usuario->cpf,
                    'data_nascimento' => $usuario->data_nascimento,
                    'telefone' => $telefone->ddi . '(' . $telefone->ddd . ')' . ' ' . $telefone->numero_telefone,
                    'empresa' => $empresaCadastrar->nome,
                    'plano' => $plano[0]['descricao']
                ], 
                'ok' => true
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'msg' => 'Ocorreu um erro ao tentar-se registrar, por gentileza, tente novamente!',
                'dados' => null,
                'ok' => false
            ], 200);
        }

    }
}
