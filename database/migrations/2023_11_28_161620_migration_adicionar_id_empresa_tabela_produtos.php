<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up() {
        Schema::table('produtos', function (Blueprint $table) {
            $table->unsignedBigInteger('empresa_id')
                ->nullable(false);
            $table->foreign('empresa_id')
                ->references('id')
                ->on('empresas');
        });
    }

    public function down() {
        Schema::table('produtos', function (Blueprint $table) {
            $table->removeColumn('empresa_id');
        });
    }
};
