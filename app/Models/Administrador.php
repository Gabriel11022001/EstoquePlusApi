<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrador extends Model
{
    use HasFactory;

    public $table = 'administradores';
    public $timestamps = false;
    protected $fillable = ['id', 'usuario_id'];

    public function usuario() {

        return $this->hasOne(Usuario::class);
    }
}
