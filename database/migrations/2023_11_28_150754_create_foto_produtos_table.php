<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up() {
        Schema::create('foto_produtos', function (Blueprint $table) {
            $table->id();
            $table->string('url_foto')
                ->nullable(false)
                ->unique('url_foto_unique_id');
            $table->unsignedBigInteger('produto_id')
                ->nullable(false);
            $table->foreign('produto_id')
                ->references('id')
                ->on('produtos');
        });
    }

    public function down() {
        Schema::dropIfExists('foto_produtos');
    }
};
