<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'id',
        'nome',
        'codigo_barras',
        'preco_compra',
        'preco_venda',
        'status',
        'data_vencimento',
        'qtd_unidades_estoque',
        'categoria_produto_id',
        'empresa_id'
    ];

    public function empresa() {

        return $this->belongsTo(Empresa::class);
    }

    public function categoria() {
        
        return $this->hasOne(CategoriaProduto::class);
    }

    public function fotos() {

        return $this->hasMany(FotoProduto::class);
    }
}
