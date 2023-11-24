<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up() {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->text('nome')
                ->nullable(false)
                ->min(3)
                ->max(255);
            $table->string('cpf')
                ->nullable(false)
                ->unique('cpf_unique_id')
                ->min(14)
                ->max(14);
            $table->string('email')
                ->nullable(false);
            $table->string('senha')
                ->nullable(false)
                ->min(8)
                ->max(25);
            $table->date('data_nascimento')
                ->nullable(false);
            $table->unsignedBigInteger('empresa_id')
                ->nullable(false);
            $table->foreign('empresa_id')
                ->references('id')
                ->on('empresas');
        });
    }

    public function down() {
        Schema::dropIfExists('usuarios');
    }
};