<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up() {
        Schema::create('categoria_produtos', function (Blueprint $table) {
            $table->id();
            $table->string('descricao')
                ->nullable(false);
            $table->boolean('status')
                ->nullable(false)
                ->default(true);
            $table->unsignedBigInteger('empresa_id')
                ->nullable(false);
            $table->foreign('empresa_id')
                ->references('id')
                ->on('empresas');
        });
    }

    public function down() {
        Schema::dropIfExists('categoria_produtos');
    }
};
