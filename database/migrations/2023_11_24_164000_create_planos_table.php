<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up() {
        Schema::create('planos', function (Blueprint $table) {
            $table->id();
            $table->string('descricao')
                ->nullable(false);
            $table->float('preco_base')
                ->nullable(false)
                ->min(0);
            $table->integer('percentual_desconto_promocao')
                ->min(0)
                ->max(100);
            $table->boolean('esta_em_promocao')
                ->nullable(false)
                ->default(false);
            $table->boolean('status')
                ->nullable(false)
                ->default(true);
            $table->integer('numero_maximo_admin')
                ->min(1);
            $table->integer('numero_maximo_vendedores')
                ->min(1);
            $table->integer('numero_maximo_fornecedores')
                ->min(1);
        });
    }

    public function down() {
        Schema::dropIfExists('planos');
    }
};
