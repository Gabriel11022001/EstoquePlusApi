<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaProduto extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'id',
        'descricao',
        'empresa_id',
        'status'
    ];

    public function empresa() {

        return $this->belongsTo(Empresa::class);
    }
}
