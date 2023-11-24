<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['id', 'nome', 'cnpj', 'status', 'cep', 'endereco', 'bairro', 'cidade', 'uf', 'numero', 'ddi', 'ddd', 'email', 'telefone'];

    public function usuarios() {

        return $this->hasMany(Usuario::class);
    }
}
