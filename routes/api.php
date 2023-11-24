<?php

use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::post('/usuario/registrar-se', [ UsuarioController::class, 'registrarse' ]);
Route::post('/usuario', [ UsuarioController::class, 'cadastrarUsuario' ]);
Route::get('/usuario/{empresaId}', [ UsuarioController::class, 'buscarTodosUsuarios' ]);
Route::get('/usuario/{id}', [ UsuarioController::class, 'buscarUsuarioPeloId' ]);