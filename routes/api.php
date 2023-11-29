<?php

use App\Http\Controllers\CategoriaProdutoController;
use App\Http\Controllers\PlanoController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::post('/categoria', [ CategoriaProdutoController::class, 'cadastrarCategoriaProduto' ]);
Route::post('/plano', [ PlanoController::class, 'cadastrarPlano' ]);
Route::post('/usuario/registrar-se', [ UsuarioController::class, 'registrarse' ]);
Route::post('/usuario', [ UsuarioController::class, 'cadastrarUsuario' ]);
Route::get('/usuario/{empresaId}', [ UsuarioController::class, 'buscarTodosUsuarios' ]);
Route::get('/usuario/{id}', [ UsuarioController::class, 'buscarUsuarioPeloId' ]);
Route::get('/categoria/{empresaId}', [ CategoriaProdutoController::class, 'buscarTodasCategoriasEmpresa' ]);
Route::get('/categoria/buscar-pelo-id/{id}', [ CategoriaProdutoController::class, 'buscarCategoriaProdutoPeloId' ]);
Route::get('/produto/{id}', [ ProdutoController::class, 'buscarProdutoPeloId' ]);