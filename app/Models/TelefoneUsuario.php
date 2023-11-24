<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelefoneUsuario extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['id', 'ddi', 'ddd', 'numero_telefone', 'usuario_id'];

    public function usuario() {

        return $this->belongsTo(Usuario::class);
    }
}
