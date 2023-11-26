<?php

namespace App\Utils;

use DateInterval;
use DateTime;
use Exception;

class ObterDataLimiteContratacaoPlano
{

    public static function obterDataLimite($dataContratacaoPlano, $tempoContratacao) {

        if (!is_numeric($tempoContratacao) || empty($tempoContratacao)) {

            throw new Exception('Tempo de contratação do plano inválido!');
        }

        if (empty($dataContratacaoPlano)) {

            throw new Exception('Data de contratação do plano inválida!');
        }

        $dataLimiteContratacao = new DateTime($dataContratacaoPlano->format('Y-m-d H:i:s'));
        $dataLimiteContratacao->add(new DateInterval('P' . $tempoContratacao . 'Y'));

        return $dataLimiteContratacao;
    }
}