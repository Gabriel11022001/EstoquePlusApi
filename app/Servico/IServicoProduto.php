<?php

namespace App\Servico;

interface IServicoProduto extends IServico
{

    function buscarTodosProdutosEmpresa($idEmpresa);
    
    function buscarPeloCodigoBarras($codigoBarras);
}