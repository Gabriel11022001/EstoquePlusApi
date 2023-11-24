<?php

namespace App\Utils;

class ValidaCamposCadastroUsuario
{
    
    public static function validarCamposCadastroUsuario($campos) {
        $errosCampos = [];

        if (empty($campos['nome'])) {
            $errosCampos['nome'] = 'O nome é um campo obrigatório!';
        }

        /* if (empty($campos['telefones']) || count($campos['telefones']) === 0) {
            $errosCampos['telefones'] = 'Informe pelo menos 1 telefone!';
        } else {
            $telefones = $campos['telefones'];

            foreach ($telefones as $telefone) {
                $erroValidacaoTelefone = ValidaTelefoneCelular::validarTelefoneCelular($telefone);

                if (isset($erroValidacaoTelefone['ddi'])) {
                    $errosCampos['telefones'][]['ddi'] = $erroValidacaoTelefone['ddi'];
                }

                if (isset($erroValidacaoTelefone['ddd'])) {
                    $errosCampos['telefones'][]['ddd'] = $erroValidacaoTelefone['ddd'];
                }

                if (isset($erroValidacaoTelefone['numero'])) {
                    $errosCampos['telefones'][]['numero'] = $erroValidacaoTelefone['numero'];
                }

            }

        } */

        return $errosCampos;
    }

    public static function validarCamposRegistrarse($campos) {

        return [];
    }
}