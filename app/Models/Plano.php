<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plano extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['id', 'descricao', 'preco_base', 'esta_em_promocao', 'percentual_desconto_promocao', 'status', 'numero_maximo_admin', 'numero_maximo_vendedores', 'numero_maximo_fornecedores'];

    public function empresas() {

        return $this->hasMany(Empresa::class);
    }
}
