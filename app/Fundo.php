<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fundo extends Model
{
    public function patrimonios()
    {
        return $this->hasMany('\App\Patrimonio', 'fundo_id', 'id');
    }
}
