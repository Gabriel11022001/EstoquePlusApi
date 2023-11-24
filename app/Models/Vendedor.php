<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    use HasFactory;

    public $table = 'vendedores';
    public $timestamps = false;
    protected $fillable = ['id', 'usuario_id'];

    public function usuario() {

        return $this->belongsTo(Usuario::class);
    }
}
