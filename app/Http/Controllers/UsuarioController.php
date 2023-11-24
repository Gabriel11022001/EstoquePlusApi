<?php

namespace App\Http\Controllers;

use App\Servico\UsuarioServico;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    private $usuarioServico;

    public function __construct(UsuarioServico $usuarioServico) {
        $this->usuarioServico = $usuarioServico;
    }

    public function buscarUsuarioPeloId($id) {
        
        return $this->usuarioServico->buscarPeloId($id);
    }

    public function cadastrarUsuario(Request $requisicao) {

        return $this->usuarioServico->cadastrar($requisicao);
    }

    public function buscarTodosUsuarios($empresaId) {

        return $this->usuarioServico->buscarTodosUsuarios($empresaId);
    }
}
