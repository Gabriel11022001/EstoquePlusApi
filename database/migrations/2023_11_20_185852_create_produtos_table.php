<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up() {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('nome')
                ->nullable(false);
            $table->string('codigo_barras')
                ->unique('codigo_barras_unique_id');
            $table->string('descricao');
            $table->date('data_vencimento');
            $table->float('preco_venda')
                ->nullable(false);
            $table->float('preco_compra')
                ->nullable(false);
            $table->integer('qtd_unidades_estoque')
                ->nullable(false);
            $table->boolean('status')
                ->nullable(false)
                ->default(true);
            $table->unsignedBigInteger('categoria_produto_id')
                ->nullable(false);
            $table->foreign('categoria_produto_id')
                ->references('id')
                ->on('categoria_produtos');
        });
    }

    public function down() {
        Schema::dropIfExists('produtos');
    }
};
