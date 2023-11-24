<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up() {
        Schema::create('telefone_usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('ddi')
                ->nullable(false);
            $table->string('ddd')
                ->nullable(false);
            $table->string('numero_telefone')
                ->nullable(false)
                ->unique('numero_telefone_unique_id');
            $table->unsignedBigInteger('usuario_id')
                ->nullable(false);
            $table->foreign('usuario_id')
                ->references('id')
                ->on('usuarios');
        });
    }

    public function down() {
        Schema::dropIfExists('telefone_usuarios');
    }
};
