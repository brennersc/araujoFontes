<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patrimonio extends Model
{
    public function fundos()
    {
        return $this->belongsTo('\App\Fundo', 'fundo_id', 'id');
    }
}