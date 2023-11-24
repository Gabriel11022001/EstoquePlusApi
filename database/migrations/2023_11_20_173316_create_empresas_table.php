<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up() {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('nome')
                ->nullable(false);
            $table->string('cnpj')
                ->nullable(false)
                ->unique('cnpj_unique_id')
                ->min(18)
                ->max(18);
            $table->boolean('status')
                ->nullable(false)
                ->default(true);
            $table->string('cep')
                ->nullable(false)
                ->min(9)
                ->max(9);
            $table->string('endereco')
                ->nullable(false);
            $table->string('bairro')
                ->nullable(false);
            $table->string('cidade')
                ->nullable(false);
            $table->string('uf')
                ->nullable(false)
                ->min(2)
                ->max(2);
            $table->string('numero')
                ->default('s/n');
            $table->string('ddi')
                ->nullable(false);
            $table->string('ddd')
                ->nullable(false);
            $table->string('telefone')
                ->nullable(false);
            $table->string('email');
        });
    }

    public function down() {
        Schema::dropIfExists('empresas');
    }
};
