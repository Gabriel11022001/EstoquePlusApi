<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoProduto extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['id', 'url_foto', 'produto_id'];

    public function produto() {

        return $this->belongsTo(Produto::class);
    }
}
