<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up() {
        Schema::table('empresas', function (Blueprint $table) {
            $table->addColumn('integer', 'tempo_contratacao_plano', [
                'nullable' => false,
                'min:1'
            ]);
        });
    }

    public function down() {

    }
};
