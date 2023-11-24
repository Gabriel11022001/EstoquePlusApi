<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['id', 'nome', 'cpf', 'email', 'senha', 'data_nascimento', 'empresa_id'];

    public function empresa() {

        return $this->belongsTo(Empresa::class);
    }

    public function telefones() {

        return $this->hasMany(TelefoneUsuario::class);
    }
}
